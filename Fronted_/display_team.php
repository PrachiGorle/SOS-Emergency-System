<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require_once "db_connect.php";

$search = "";

if(isset($_GET['search'])){
    $search = $conn->real_escape_string($_GET['search']);
}

/* SEARCH QUERY */
if($search != ""){
$sql = "
SELECT 
    a.Incident_ID,
    a.Team_ID,
    a.Assigned_At,
    i.Type AS Incident_Type,
    i.City AS Incident_City,
    t.Name AS Team_Name,
    t.Status AS Team_Status
FROM assigned_to a
JOIN INCIDENT i ON a.Incident_ID = i.Incident_ID
JOIN RESCUE_TEAM t ON a.Team_ID = t.Team_ID
WHERE 
    a.Incident_ID LIKE '%$search%' OR
    t.Name LIKE '%$search%' OR
    i.City LIKE '%$search%' OR
    t.Status LIKE '%$search%'
ORDER BY a.Assigned_At DESC
";
}
else{

$sql = "
SELECT 
    a.Incident_ID,
    a.Team_ID,
    a.Assigned_At,
    i.Type AS Incident_Type,
    i.City AS Incident_City,
    t.Name AS Team_Name,
    t.Status AS Team_Status
FROM assigned_to a
JOIN INCIDENT i ON a.Incident_ID = i.Incident_ID
JOIN RESCUE_TEAM t ON a.Team_ID = t.Team_ID
ORDER BY a.Assigned_At DESC
";

}

$result = $conn->query($sql);

$assignments = [];

if($result){
    $assignments = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<title>Assigned Rescue Teams</title>

<style>

body{
font-family:Arial, sans-serif;
background:#f4f6f8;
margin:0;
}

/* HEADER */
header{
background:#1a6b3c;
color:white;
padding:8px;
display:flex;
justify-content:space-between;
align-items:center;
}

.header-right{
display:flex;
align-items:center;
gap:10px;
}

.search-form input{
padding:8px;
width:200px;
border:1px solid #ccc;
border-radius:5px;
}

.search-form button{
padding:8px 15px;
background:#145530;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
}

.back-btn{
background:white;
color:#1a6b3c;
padding:8px 12px;
border-radius:5px;
text-decoration:none;
font-weight:bold;
}

.back-btn:hover{
background:#e8f5ee;
}

.reset-btn{
color:white;
text-decoration:none;
margin-left:5px;
margin-right:20px;
}

/* CONTAINER */

.container{
width:90%;
max-width:1000px;
margin:40px auto;
background:white;
padding:20px;
border-radius:8px;
box-shadow:0 3px 10px rgba(0,0,0,0.1);
}

h2{
color:#1a6b3c;
margin-top:0;
}

/* TABLE */

table{
width:100%;
border-collapse: collapse;
margin-top:20px;
}

th{
background:#1a6b3c;
color:white;
padding:11px;
text-align:left;
font-size:0.82rem;
}

td{
padding:12px;
border-bottom:1px solid #ddd;
font-size:0.9rem;
}

tr:hover{
background:#f0faf4;
}

/* STATUS BADGES */

.status{
font-weight:bold;
padding:4px 10px;
border-radius:12px;
display:inline-block;
color:white;
}

.status-Available{
background:#28a745;
}

.status-Deployed{
background:#dc3545;
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

<form method="GET" class="search-form">

<input type="text"
name="search"
placeholder="Search..."
value="<?= htmlspecialchars($search ?? '') ?>">

<button type="submit">Search</button>

<a href="display_team.php" class="reset-btn">Reset</a>

</form>

</div>

</header>

<div class="container">

<h2>Assigned Rescue Teams</h2>

<?php if(empty($assignments)): ?>

<p>No assignments found.</p>

<?php else: ?>

<table>

<thead>

<tr>
<th>Incident ID</th>
<th>Incident Type</th>
<th>City</th>
<th>Team ID</th>
<th>Team Name</th>
<th>Team Status</th>
<th>Assigned At</th>
</tr>

</thead>

<tbody>

<?php foreach($assignments as $a): ?>

<tr>

<td><?= htmlspecialchars($a['Incident_ID']) ?></td>

<td><?= htmlspecialchars($a['Incident_Type']) ?></td>

<td><?= htmlspecialchars($a['Incident_City']) ?></td>

<td><?= htmlspecialchars($a['Team_ID']) ?></td>

<td><?= htmlspecialchars($a['Team_Name']) ?></td>

<td>
<span class="status status-<?= htmlspecialchars($a['Team_Status']) ?>">
<?= htmlspecialchars($a['Team_Status']) ?>
</span>
</td>

<td><?= htmlspecialchars($a['Assigned_At']) ?></td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<?php endif; ?>

</div>

</body>
</html>