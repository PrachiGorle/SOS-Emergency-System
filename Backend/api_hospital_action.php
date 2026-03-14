<?php
// ============================================================
// FILE: api_hospital_action.php
// PURPOSE: Handles CRUD actions (currently Delete) for Hospital
// ============================================================

error_reporting(E_ALL);
ini_set('display_errors',1);

require_once "db_connect.php";

// Only POST requests are allowed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "POST request required";
    exit();
}

// Get the action and hospital_id
$action = $_POST['action'] ?? '';
$hospital_id = (int)($_POST['hospital_id'] ?? 0);

if ($action === 'delete') {
    if ($hospital_id <= 0) {
        echo "Invalid Hospital ID";
        exit();
    }

    // Prepare DELETE statement
    $sql = "DELETE FROM hospital WHERE Hospital_ID = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "SQL Error: " . $conn->error;
        exit();
    }

    $stmt->bind_param("i", $hospital_id);

    if ($stmt->execute()) {
        echo "SUCCESS";
    } else {
        echo "Failed to delete: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit();
} else {
    echo "Invalid action";
    exit();
}
?>