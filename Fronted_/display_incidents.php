<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require_once "db_connect.php";

/* AJAX ACTIONS */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    $action = $_POST['action'];
    $incident_id = (int) ($_POST['incident_id'] ?? 0);

    if ($action === 'delete') {

        $stmt = $conn->prepare("DELETE FROM INCIDENT WHERE Incident_ID = ?");
        $stmt->bind_param("i", $incident_id);
        $stmt->execute();
     echo "SUCCESS";

        $stmt->close();
        exit();
    }

    if ($action === 'update_status') {

        $new_status = trim($_POST['new_status'] ?? '');

        $allowed_status = ['Active','Controlled','Closed'];

        if(!in_array($new_status,$allowed_status)){
            echo "INVALID_STATUS";
            exit();
        }

        $stmt = $conn->prepare("UPDATE INCIDENT SET Status=? WHERE Incident_ID=?");
        $stmt->bind_param("si",$new_status,$incident_id);
        $stmt->execute();

        echo $stmt->affected_rows > 0 ? "SUCCESS" : "FAIL";

        $stmt->close();
        exit();
    }
}

/* SEARCH SYSTEM */
$search = "";

if(isset($_GET['search'])){
    $search = $conn->real_escape_string($_GET['search']);
}

if($search != ""){

$sql = "
SELECT 
    Incident_ID,
    Trigger_Call_ID,
    Type,
    Severity,
    City,
    Reported_At,
    Status
FROM INCIDENT
WHERE 
    Incident_ID LIKE '%$search%' OR
    Type LIKE '%$search%' OR
    City LIKE '%$search%'
ORDER BY Reported_At DESC
";

}
else{

$sql = "
SELECT 
    Incident_ID,
    Trigger_Call_ID,
    Type,
    Severity,
    City,
    Reported_At,
    Status
FROM INCIDENT
ORDER BY Reported_At DESC
";

}

$result = $conn->query($sql);

$incidents = [];

if($result){
    $incidents = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<title>All Incidents</title>
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

.back-btn{
background:white;
color:#1a6b3c;
padding:8px 12px;
border-radius:5px;
text-decoration:none;
font-weight:bold;
margin-left:500px;
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

nav a{color:white;text-decoration:none;margin-left:15px;font-weight:bold;}
nav a:hover{text-decoration:underline;}

table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.12); }
th { background: #1a6b3c; color: #fff; padding: 11px 14px; text-align: left; font-size: 0.82rem; text-transform: uppercase; }
td { padding: 12px 14px; border-bottom: 1px solid #eee; font-size: 0.9rem; vertical-align: middle; }
tr:hover td { background: #f0faf4; }

.severity { font-weight: bold; padding: 4px 10px; border-radius: 12px; display: inline-block; color: #fff; }
.sev-1 { background: #28a745; } /* Low */
.sev-2 { background: #6c757d; } /* Minor */
.sev-3 { background: #ffc107; } /* Medium */
.sev-4 { background: #fd7e14; } /* High */
.sev-5 { background: #dc3545; } /* Critical */

.status { font-weight: bold; padding: 4px 10px; border-radius: 12px; display: inline-block; color: #fff; }
.status-Active { background:#dc3545; }
.status-Controlled { background:#ffc107; color:#333; }
.status-Closed { background:#28a745; }

.btn { padding: 4px 10px; margin: 2px; border-radius: 4px; cursor: pointer; border: none; font-size: 0.85rem; }
.btn-delete { background: #dc3545; color: #fff; }
.btn-update { background: #007bff; color: #fff; }


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

<a href="index.php" class="back-btn">⬅ Dashboard</a>
<form method="GET" class="search-form">

<input type="text"
name="search"
placeholder="Search Incident ID, or City"
value="<?= htmlspecialchars($search ?? '') ?>"
style="padding:8px;width:250px;border:1px solid #ccc;border-radius:5px;">

<button type="submit"
style="padding:8px 15px;background:#1a6b3c;color:white;border:none;border-radius:5px;">
Search
</button>

<a href="display_incidents.php"
style="margin-left:10px;color:white;">Reset</a>

</form>
</div>
</header>

<div style="padding:20px;">
<h2>All Incidents</h2>


<table>
<thead>
<tr>
    <th>ID</th>
    <th>Call ID</th>
    <th>Type</th>
    <th>Severity</th>
    <th>City</th>
    <th>Reported At</th>
    <th>Status</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php if(empty($incidents)): ?>
<tr>
    <td colspan="8" style="text-align:center; padding:20px;">No incidents found.</td>
</tr>
<?php else: ?>
<?php foreach($incidents as $inc): ?>
<tr id="incident-<?= $inc['Incident_ID'] ?>">
    <td><?= $inc['Incident_ID'] ?></td>
    <td><?= $inc['Trigger_Call_ID'] ?></td>
    <td><?= htmlspecialchars($inc['Type']) ?></td>
    <td><span class="severity sev-<?= $inc['Severity'] ?>"><?= $inc['Severity'] ?></span></td>
    <td><?= htmlspecialchars($inc['City']) ?></td>
    <td><?= $inc['Reported_At'] ?></td>
    <td><span class="status status-<?= $inc['Status'] ?>"><?= $inc['Status'] ?></span></td>
    <td>
        <!-- Update Status -->
        <select onchange="updateStatus(<?= $inc['Incident_ID'] ?>, this.value)">
            <option value="">Change Status</option>
<option value="Active">Active</option>
<option value="Controlled">Controlled</option>
<option value="Closed">Closed</option>
        </select>
        <button class="btn btn-delete" onclick="deleteIncident(<?= $inc['Incident_ID'] ?>)">Delete</button>
    </td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>

<script>
// Delete Incident
function deleteIncident(id) {
    if (!confirm("Are you sure you want to delete this incident?")) return;

    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('incident_id', id);

  fetch('api_incident_action.php', { method: 'POST', body: formData })
        .then(res => res.text())
        .then(data => {
            if (data.trim() === 'SUCCESS') {
                alert('Incident deleted successfully!');
                document.getElementById('incident-' + id).remove();
            } else {
                alert('Failed to delete incident: ' + data);
            }
        });
}

// Update Status
function updateStatus(id, status) {
    if (status === '') return;

    const formData = new FormData();
    formData.append('action', 'update_status');
    formData.append('incident_id', id);
    formData.append('new_status', status);

    fetch('api_incident_action.php', { method: 'POST', body: formData })
        .then(res => res.text())
        .then(data => {
            if (data.trim() === 'SUCCESS') {
                alert('Status updated!');
                location.reload(); // Refresh to update color
            } else {
                alert('Failed to update status: ' + data);
            }
        });
}
</script>

</body>
</html>