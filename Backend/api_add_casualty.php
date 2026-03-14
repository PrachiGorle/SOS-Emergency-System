<?php
require_once 'db_connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "This endpoint only accepts POST requests.";
    exit;
}

// Collect inputs
$incident_id = (int)($_POST['incident_id'] ?? 0);
$triage_color = $_POST['triage_color'] ?? '';
$name = trim($_POST['name'] ?? 'Unknown');
$age = $_POST['age'] !== '' ? (int)$_POST['age'] : 'NULL';
$admitted_hospital_id = $_POST['admitted_hospital_id'] !== '' ? (int)$_POST['admitted_hospital_id'] : 'NULL';

// Validation
if ($incident_id === 0 || $triage_color === '') {
    echo "Incident and triage color are required.";
    exit;
}

// Check Incident exists
$result = $conn->query("SELECT Incident_ID FROM INCIDENT WHERE Incident_ID = $incident_id");
if ($result->num_rows === 0) {
    echo "Incident not found.";
    exit;
}

// Get next Sequence_No
$res = $conn->query("SELECT COALESCE(MAX(Sequence_No),0) AS max_seq FROM CASUALTY WHERE Incident_ID = $incident_id");
$row = $res->fetch_assoc();
$next_seq = $row['max_seq'] + 1;

// Insert into CASUALTY
$sql = "INSERT INTO CASUALTY 
        (Incident_ID, Sequence_No, Name, Age, Triage_Color, Admitted_Hospital_ID)
        VALUES ($incident_id, $next_seq, '$name', $age, '$triage_color', $admitted_hospital_id)";

if ($conn->query($sql) === TRUE) {
    echo "SUCCESS"; // we return this so frontend can detect it
} else {
    echo "ERROR: " . $conn->error;
}

$conn->close();
?>