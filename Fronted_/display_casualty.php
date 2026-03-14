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
            c.incident_ID,
            c.Sequence_No,
            c.Name AS Casualty_Name,
            c.Age,
            c.Triage_Color,
            c.Admitted_Hospital_ID,
            i.Type AS Incident_Type,
            i.City AS Incident_City,
            h.Name AS Hospital_Name
        FROM CASUALTY c
        LEFT JOIN INCIDENT i ON c.incident_ID = i.incident_ID
        LEFT JOIN HOSPITAL h ON c.Admitted_Hospital_ID = h.Hospital_ID
        WHERE 
            c.Name LIKE '%$search%'
            OR c.Triage_Color LIKE '%$search%'
            OR i.City LIKE '%$search%'
            OR c.incident_ID LIKE '%$search%'
        ORDER BY c.incident_ID, c.Sequence_No
    ";
}
else{
    $sql = "
        SELECT 
            c.incident_ID,
            c.Sequence_No,
            c.Name AS Casualty_Name,
            c.Age,
            c.Triage_Color,
            c.Admitted_Hospital_ID,
            i.Type AS Incident_Type,
            i.City AS Incident_City,
            h.Name AS Hospital_Name
        FROM CASUALTY c
        LEFT JOIN INCIDENT i ON c.incident_ID = i.incident_ID
        LEFT JOIN HOSPITAL h ON c.Admitted_Hospital_ID = h.Hospital_ID
        ORDER BY c.incident_ID, c.Sequence_No
    ";
}

$result = $conn->query($sql);

$casualties = [];
if($result){
    $casualties = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<title>View Casualties</title>

<style>
 body{
font-family:Arial, sans-serif;
background:#f4f6f8;
margin:0;
}
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


.reset-btn{
color:white;
text-decoration:none;
margin-left:5px;
margin-right:20px;
}


.container{
width:90%;
max-width:1000px;
margin:40px auto;
background:white;
padding:20px;
border-radius:8px;
box-shadow:0 3px 10px rgba(0,0,0,0.1);
}

h2{color:#1a6b3c;margin-top:0;}

table{
width:100%;
border-collapse: collapse;
margin-top:20px;
}

th, td{
padding:12px 10px;
border-bottom:1px solid #ddd;
text-align:left;
}

th{
background:#1a6b3c;
color:white;
}

tr:hover{
background:#f0faf4;
}

.triage{
padding:4px 10px;
border-radius:12px;
font-weight:bold;
}

.triage-Red {background:#ffdddd;color:#cc0000;}
.triage-Yellow {background:#fff3cd;color:#856404;}
.triage-Green {background:#d4edda;color:#155724;}
.triage-Black {background:#aaa;color:#000;}
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
<form method="GET" class="search_form">

<input type="text"
name="search"
placeholder="Search Name,ID or City"
value="<?= htmlspecialchars($search ?? '') ?>"
style="padding:8px;width:250px;border:1px solid #ccc;border-radius:5px;">

<button type="submit"
style="padding:8px 15px;background:#1a6b3c;color:white;border:none;border-radius:5px;">
Search
</button>

<a href="display_casualty.php"
style="margin-left:10px;color:white;">Reset</a>

</form>
</div>
</header>

<div class="container">

<h2>All Casualties</h2>

<?php if(empty($casualties)): ?>
<p>No casualties found.</p>
<?php else: ?>

<table>

<thead>
<tr>
<th>Incident ID</th>
<th>Sequence No</th>
<th>Name</th>
<th>Age</th>
<th>Triage Color</th>
<th>Admitted Hospital</th>
<th>Incident Type</th>
<th>Incident City</th>
</tr>
</thead>

<tbody>

<?php foreach($casualties as $c): ?>

<tr>

<td><?= htmlspecialchars($c['incident_ID']) ?></td>

<td><?= htmlspecialchars($c['Sequence_No']) ?></td>

<td><?= htmlspecialchars($c['Casualty_Name']) ?></td>

<td><?= htmlspecialchars($c['Age']) ?></td>

<td>
<span class="triage triage-<?= htmlspecialchars($c['Triage_Color']) ?>">
<?= htmlspecialchars($c['Triage_Color']) ?>
</span>
</td>

<td><?= htmlspecialchars($c['Hospital_Name'] ?? 'Not Assigned') ?></td>

<td><?= htmlspecialchars($c['Incident_Type'] ?? '-') ?></td>

<td><?= htmlspecialchars($c['Incident_City'] ?? '-') ?></td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<?php endif; ?>

</div>

</body>
</html>