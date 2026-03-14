<?php
require_once "db_connect.php";

$call_id = $_POST['trigger_call_id'];
$type = $_POST['type'];
$severity = $_POST['severity'];
$city = $_POST['city'];

$query = "INSERT INTO incident (trigger_call_id,type,severity,city)
VALUES ('$call_id','$type','$severity','$city')";

if(mysqli_query($conn,$query)){
    
    echo "<script>
    alert('Incident Added Successfully');
    window.location.href='index.php';
    </script>";
    exit();

}else{
    echo mysqli_error($conn);
}
?>