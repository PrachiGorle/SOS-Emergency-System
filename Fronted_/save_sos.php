<?php
require_once "db_connect.php";

/* Get JSON data from fetch */
$data = json_decode(file_get_contents("php://input"), true);

$lat = $data['latitude'];
$lng = $data['longitude'];

/* Insert SOS call */

$stmt = $conn->prepare("INSERT INTO EMERGENCY_CALL (Latitude, Longitude) VALUES (?,?)");
$stmt->bind_param("dd",$lat,$lng);
$stmt->execute();

/* Get Call ID */
$call_id = $stmt->insert_id;

echo "SUCCESS";

$stmt->close();
$conn->close();
?>