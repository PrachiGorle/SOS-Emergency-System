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
    $sql = "SELECT Hospital_ID, Name, Available_Beds, Street, City, Zip_code
            FROM hospital
            WHERE Name LIKE '%$search%' 
            OR City LIKE '%$search%' 
            OR Zip_code LIKE '%$search%'
            ORDER BY Hospital_ID ASC";
}
else{
    $sql = "SELECT Hospital_ID, Name, Available_Beds, Street, City, Zip_code
            FROM hospital
            ORDER BY Hospital_ID ASC";
}

$result = $conn->query($sql);

$hospitals = [];
if($result){
    $hospitals = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<title>View Hospitals</title>
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

.container{width:90%; max-width:1000px;margin:40px auto;background:white;padding:20px;border-radius:8px;box-shadow:0 3px 10px rgba(0,0,0,0.1);}
h2{color:#1a6b3c;margin-top:0;}

table{width:100%; border-collapse: collapse; margin-top:20px;}
th, td{padding:12px 10px;border-bottom:1px solid #ddd;text-align:left;}
th{background:#1a6b3c;color:white;}
tr:hover{background:#f0faf4;}
button.delete-btn{background:#cc0000;color:white;border:none;padding:6px 10px;border-radius:3px;cursor:pointer;}
button.delete-btn:hover{background:#ff4d4d;}

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

<input type="text" name="search"
placeholder="Search Hospital Name or City"
value="<?= htmlspecialchars($search ?? '') ?>">

<button type="submit">Search</button>

<a href="display_hospital.php"
style="margin-left:10px;color:white">Reset</a>

</form>
</div>
</header>


<div class="container">
<h2>All Hospitals</h2>

<?php if(empty($hospitals)): ?>
<p>No hospitals found in the database.</p>
<?php else: ?>
<table>
<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Available Beds</th>
<th>Street</th>
<th>City</th>
<th>Zip Code</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach($hospitals as $h): ?>
<tr id="hospital-<?= $h['Hospital_ID'] ?>">
<td><?= htmlspecialchars($h['Hospital_ID']) ?></td>
<td><?= htmlspecialchars($h['Name']) ?></td>
<td><?= htmlspecialchars($h['Available_Beds']) ?></td>
<td><?= htmlspecialchars($h['Street']) ?></td>
<td><?= htmlspecialchars($h['City']) ?></td>
<td><?= htmlspecialchars($h['Zip_code']) ?></td>
<td>
    <button class="delete-btn" onclick="deleteHospital(<?= $h['Hospital_ID'] ?>)">Delete</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>
</div>

<script>
// Delete hospital function
function deleteHospital(id){
    if(!confirm("Are you sure you want to delete this hospital?")) return;

    const fd = new FormData();
    fd.append('action','delete');
    fd.append('hospital_id', id);

    fetch('api_hospital_action.php', { method:'POST', body:fd })
        .then(res => res.text())
        .then(data => {
            data = data.trim();
            if(data === 'SUCCESS'){
                alert('Hospital deleted!');
                const row = document.getElementById('hospital-' + id);
                if(row) row.remove();
            } else {
                alert('Failed to delete: ' + data);
            }
        })
        .catch(err => alert('Server error: ' + err));
}
</script>

</body>
</html>