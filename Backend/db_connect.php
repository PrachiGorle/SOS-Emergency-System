<?php

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "sos_audit_system";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("DATABASE CONNECTION FAILED: " . $conn->connect_error);
}

?>