<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

require_once "db_connect.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

$incident_id = $_POST["incident_id"];
$team_id = $_POST["team_id"];

if(empty($incident_id) || empty($team_id)){
echo "ERROR: Incident ID and Team ID required";
exit();
}

/* CHECK DUPLICATE ASSIGNMENT */

$check = $conn->prepare("SELECT * FROM assigned_to WHERE Incident_ID=? AND Team_ID=?");
$check->bind_param("ii",$incident_id,$team_id);
$check->execute();
$result = $check->get_result();

if($result->num_rows > 0){
echo "ERROR: Team already assigned to this incident";
exit();
}

/* INSERT ASSIGNMENT */

$sql = "INSERT INTO assigned_to (Incident_ID, Team_ID, Assigned_At)
VALUES (?, ?, NOW())";

$stmt = $conn->prepare($sql);

if(!$stmt){
die("SQL ERROR: ".$conn->error);
}

$stmt->bind_param("ii",$incident_id,$team_id);
$stmt->execute();

/* UPDATE TEAM STATUS */

$update = $conn->prepare("UPDATE rescue_team SET Status='Deployed' WHERE Team_ID=?");
$update->bind_param("i",$team_id);
$update->execute();

echo "SUCCESS";

}
else{

echo "POST request required";

}

?>