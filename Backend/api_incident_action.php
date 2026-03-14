<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require_once "db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    $action = $_POST['action'];
    $incident_id = (int) ($_POST['incident_id'] ?? 0);

    if ($incident_id <= 0) {
        echo "INVALID_ID";
        exit();
    }

    // Delete
    if ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM INCIDENT WHERE Incident_ID = ?");
        $stmt->bind_param("i", $incident_id);
        $stmt->execute();

        echo $stmt->affected_rows > 0 ? "SUCCESS" : "FAIL";

        $stmt->close();
        exit();
    }

    // Update Status
    if ($action === 'update_status') {

        $new_status = trim($_POST['new_status'] ?? '');

        $allowed_status = ['Reported','Ongoing','Resolved'];

        if (!in_array($new_status, $allowed_status)) {
            echo "INVALID_STATUS";
            exit();
        }

        $stmt = $conn->prepare("UPDATE INCIDENT SET Status=? WHERE Incident_ID=?");
        $stmt->bind_param("si", $new_status, $incident_id);
        $stmt->execute();

        echo "SUCCESS";

        $stmt->close();
        exit();
    }

} else {
    echo "POST request required";
}
?>