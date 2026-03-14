<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require_once "db_connect.php";

$sql = "SELECT Call_ID, Caller_Phone, Latitude, Longitude, Call_Timestamp
        FROM EMERGENCY_CALL
        ORDER BY Call_ID DESC";

$result = $conn->query($sql);

$calls = [];
if($result){
$calls = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<title>Emergency Calls</title>

<style>

body{font-family:Arial;background:#f4f6f8;margin:0;}

header{
background:#1a6b3c;
color:white;
padding:8px;
display:flex;
justify-content:space-between;
align-items:center;
}

nav a{
color:white;
text-decoration:none;
margin-left:15px;
font-weight:bold;
}
nav a:hover{text-decoration:underline;}
.container{
width:90%;
max-width:900px;
margin:40px auto;
background:white;
padding:15px;
border-radius:8px;
box-shadow:0 3px 10px rgba(0,0,0,0.1);
}

table{
width:100%;
border-collapse:collapse;
}

th,td{
padding:12px;
border-bottom:1px solid #ddd;
text-align:left;
}

th{
background:#1a6b3c;
color:white;
}
.back-btn{
background:white;
color:#1a6b3c;
padding:8px 12px;
border-radius:5px;
text-decoration:none;
font-weight:bold;
margin-right:10px;
}

.back-btn:hover{
background:#e8f5ee;
}
.logo-area{
display:flex;
align-items:center;
gap:10px;
}
.logo{
font-size:28px;
}

</style>

</head>

<body>

<header>
<div class="logo-area">
<i class="fa-solid fa-truck-medical logo"></i>
<h1>SOS Emergency System</h1>
</div>

<div class="header-right">

<a href="index.php" class="back-btn">⬅ Dashboard</a>
</div>

</header>


<div class="container">

<h2>All Emergency Calls</h2>

<?php if(empty($calls)): ?>

<p>No emergency calls found.</p>

<?php else: ?>

<table>

<tr>
<th>ID</th>
<th>Caller Phone</th>
<th>Latitude</th>
<th>Longitude</th>
<th>Timestamp</th>
</tr>

<?php foreach($calls as $c): ?>

<tr>
<td><?= $c["Call_ID"] ?></td>
<td><?= $c["Caller_Phone"] ?></td>
<td><?= $c["Latitude"] ?></td>
<td><?= $c["Longitude"] ?></td>
<td><?= $c["Call_Timestamp"] ?></td>
</tr>

<?php endforeach; ?>

</table>

<?php endif; ?>

</div>

</body>
</html>