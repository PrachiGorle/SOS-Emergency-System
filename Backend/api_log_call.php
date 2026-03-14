<?php
require_once "db_connect.php";

$caller_phone = $_POST['caller_phone'] ?? '';
$latitude = $_POST['latitude'] ?? '';
$longitude = $_POST['longitude'] ?? '';

if($caller_phone && $latitude && $longitude){
    $stmt = $conn->prepare("INSERT INTO emergency_call (Caller_Phone, Latitude, Longitude) VALUES (?, ?, ?)");
    $stmt->bind_param("sdd", $caller_phone, $latitude, $longitude);
    $stmt->execute();

    if($stmt->affected_rows > 0){
        $call_id = $stmt->insert_id;
        echo json_encode(["status"=>"success","call_id"=>$call_id]);
    } else {
        echo json_encode(["status"=>"fail"]);
    }

    $stmt->close();
} else {
    echo json_encode(["status"=>"fail"]);
}

$conn->close();
?>