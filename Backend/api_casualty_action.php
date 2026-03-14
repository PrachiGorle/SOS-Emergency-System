<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
require_once "db_connect.php";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])){
    
    $action = $_POST['action'];
    $casualty_id = (int) ($_POST['casualty_id'] ?? 0);

    if($casualty_id <= 0){
        echo "INVALID_ID";
        exit();
    }

    if($action === 'delete'){
        $stmt = $conn->prepare("DELETE FROM CASUALTY WHERE Casualty_ID = ?");
        $stmt->bind_param("i", $casualty_id);
        $stmt->execute();
        echo $stmt->affected_rows > 0 ? "SUCCESS" : "FAIL";
        $stmt->close();
        exit();
    }

    if($action === 'update_status'){
        $status = trim($_POST['status'] ?? '');
        $allowed_status = ['Red','Yellow','Green','Black'];
        if(!in_array($status, $allowed_status)){
            echo "INVALID_STATUS";
            exit();
        }
        $stmt = $conn->prepare("UPDATE CASUALTY SET Triage_Color = ? WHERE Casualty_ID = ?");
        $stmt->bind_param("si", $status, $casualty_id);
        $stmt->execute();
        echo $stmt->affected_rows > 0 ? "SUCCESS" : "FAIL";
        $stmt->close();
        exit();
    }

} else {
    echo "POST request required";
}
?>