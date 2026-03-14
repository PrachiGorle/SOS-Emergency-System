<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

require_once "db_connect.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

$name = $_POST["name"];
$beds = $_POST["beds"];
$street = $_POST["street"];
$city = $_POST["city"];
$zip = $_POST["zip"];

if(empty($name) || empty($beds) || empty($street) || empty($city) || empty($zip)){
echo "ERROR: All fields required";
exit();
}

$sql = "INSERT INTO hospital (Name, Available_Beds, Street, City, Zip_code)
VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if(!$stmt){
die("SQL Error: " . $conn->error);
}

$stmt->bind_param("sisss",$name,$beds,$street,$city,$zip);

if($stmt->execute()){
echo "Hospital added successfully";
}else{
echo "Insert failed: " . $stmt->error;
}

$stmt->close();
$conn->close();

}
else{

echo "POST request required";

}
?>