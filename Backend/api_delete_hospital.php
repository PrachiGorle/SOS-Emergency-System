<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require_once "db_connect.php";

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $hospital_id = intval($_POST['hospital_id'] ?? 0);

    if($hospital_id <= 0){
        echo "ERROR: Invalid hospital ID";
        exit();
    }

    $sql = "DELETE FROM hospital WHERE Hospital_ID = ?";
    $stmt = $conn->prepare($sql);

    if(!$stmt){
        die("SQL ERROR: " . $conn->error);
    }

    $stmt->bind_param("i", $hospital_id);
    $stmt->execute();

    if($stmt->affected_rows > 0){
        echo "SUCCESS";
    } else {
        echo "ERROR: Hospital not found or could not be deleted";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "POST request required";
}
?>