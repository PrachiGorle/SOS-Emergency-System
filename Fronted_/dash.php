<?php
require_once "db_connect.php";

/* COUNT DATA FROM DATABASE */

$calls = $conn->query("SELECT COUNT(*) as total FROM EMERGENCY_CALL")->fetch_assoc()['total'];

$incidents = $conn->query("SELECT COUNT(*) as total FROM INCIDENT")->fetch_assoc()['total'];

$hospitals = $conn->query("SELECT COUNT(*) as total FROM HOSPITAL")->fetch_assoc()['total'];

$casualties = $conn->query("SELECT COUNT(*) as total FROM CASUALTY")->fetch_assoc()['total'];

$teams = $conn->query("SELECT COUNT(*) as total FROM RESCUE_TEAM")->fetch_assoc()['total'];

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<title>SOS Dashboard</title>

<style>

body{
font-family:Arial;
background:#f4f6f8;
margin:0;
}

/* HEADER */

header{
background:#1a6b3c;
color:white;
padding:20px;
display:flex;
justify-content:space-between;
align-items:center;
}

header h1{
margin:0;
}

nav a{
color:white;
text-decoration:none;
margin-left:20px;
font-weight:bold;
}

nav a:hover{
text-decoration:underline;
}

/* DASHBOARD GRID */

.container{
width:90%;
max-width:1100px;
margin:40px auto;
}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
gap:20px;
}

/* CARD */

.card{
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 3px 10px rgba(0,0,0,0.1);
text-align:center;
}

.card h2{
margin:10px 0;
font-size:35px;
color:#1a6b3c;
}

.card p{
margin:0;
font-size:16px;
color:#555;
}

</style>

</head>

<body>

<header>

<h1>🚨 SOS Emergency System Dashboard</h1>

<nav>
<a href="index.html">Home</a>
<a href="log_call.html">Log Call</a>
<a href="create_incident.html">Create Incident</a>
<a href="add_casualty.html">Add Casualty</a>
<a href="assign_team.html">Assign Team</a>
<a href="add_hospital.html">Add Hospital</a>
</nav>

</header>


<div class="container">

<div class="grid">

<div class="card">
<h2><?php echo $calls; ?></h2>
<p>Emergency Calls</p>
</div>

<div class="card">
<h2><?php echo $incidents; ?></h2>
<p>Incidents</p>
</div>

<div class="card">
<h2><?php echo $casualties; ?></h2>
<p>Casualties</p>
</div>

<div class="card">
<h2><?php echo $hospitals; ?></h2>
<p>Hospitals</p>
</div>

<div class="card">
<h2><?php echo $teams; ?></h2>
<p>Rescue Teams</p>
</div>

</div>

</div>

</body>
</html>