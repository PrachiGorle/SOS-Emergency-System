<?php
require_once 'db_connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "This endpoint only accepts POST requests.";
    exit;
}

// Collect inputs
$incident_id = isset($_POST['incident_id']) ? (int)$_POST['incident_id'] : 0;
$triage_color = isset($_POST['triage_color']) ? trim($_POST['triage_color']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : 'Unknown';
$age = ($_POST['age'] !== '') ? (int)$_POST['age'] : NULL;
$admitted_hospital_id = (!empty($_POST['admitted_hospital_id'])) ? (int)$_POST['admitted_hospital_id'] : NULL;

// Basic validation
if ($incident_id === 0 || $triage_color === '') {
    echo "Incident ID and triage color are required.";
    exit;
}

// Check if Incident exists
$stmt = $conn->prepare("SELECT Incident_ID FROM INCIDENT WHERE Incident_ID = ?");
$stmt->bind_param("i", $incident_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Incident not found.";
    exit;
}

// If hospital ID provided, check if it exists
if ($admitted_hospital_id !== NULL) {
    $stmt = $conn->prepare("SELECT Hospital_ID FROM HOSPITAL WHERE Hospital_ID = ?");
    $stmt->bind_param("i", $admitted_hospital_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Invalid hospital ID.";
        exit;
    }
}

// Get next sequence number for casualty
$stmt = $conn->prepare("SELECT COALESCE(MAX(Sequence_No),0) AS max_seq FROM CASUALTY WHERE Incident_ID = ?");
$stmt->bind_param("i", $incident_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$next_seq = $row['max_seq'] + 1;

// Insert casualty
$stmt = $conn->prepare("
    INSERT INTO CASUALTY 
    (Incident_ID, Sequence_No, Name, Age, Triage_Color, Admitted_Hospital_ID)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "iisisi",
    $incident_id,
    $next_seq,
    $name,
    $age,
    $triage_color,
    $admitted_hospital_id
);

if ($stmt->execute()) {
    echo "SUCCESS";
} else {
    echo "ERROR: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
