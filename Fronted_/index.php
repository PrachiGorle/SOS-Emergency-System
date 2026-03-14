<?php
require_once "db_connect.php";

/* DASHBOARD COUNTS */

$calls = $conn->query("SELECT COUNT(*) as total FROM emergency_call")->fetch_assoc()['total'];
$incidents = $conn->query("SELECT COUNT(*) as total FROM incident")->fetch_assoc()['total'];
$casualties = $conn->query("SELECT COUNT(*) as total FROM casualty")->fetch_assoc()['total'];
$hospitals = $conn->query("SELECT COUNT(*) as total FROM hospital")->fetch_assoc()['total'];
$teams = $conn->query("SELECT COUNT(*) as total FROM rescue_team")->fetch_assoc()['total'];
$last_sos = $conn->query("SELECT Latitude, Longitude FROM emergency_call ORDER BY call_Timestamp DESC LIMIT 1");
$lastLocation = $last_sos->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>SOS Emergency System Dashboard</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Leaflet Map (FREE) -->

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>

/* RESET */

*{
box-sizing:border-box;
margin:0;
padding:0;
}

body{
font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
background:#f4f6f8;
color:#333;
}

/* HEADER */

.top-header{
background:#1a6b3c;
color:white;
padding:20px 40px;
display:flex;
justify-content:space-between;
align-items:center;
flex-wrap:wrap;
}

.logo-area{
display:flex;
align-items:center;
gap:10px;
}

.logo{
font-size:28px;
}

.header-info{
display:flex;
gap:20px;
font-size:14px;
}

.info-box{
background:rgba(255,255,255,0.1);
padding:6px 12px;
border-radius:6px;
}

/* LAYOUT */

.layout{
display:flex;
min-height:calc(100vh - 100px);
}

/* SIDEBAR */

.sidebar{
width:240px;
background:white;
border-right:1px solid #ddd;
padding:20px;
}

.sidebar h3{
color:#1a6b3c;
margin-bottom:10px;
}

.sidebar a{
display:block;
text-decoration:none;
color:#333;
padding:10px;
margin-bottom:5px;
border-radius:6px;
font-weight:500;
}

.sidebar a:hover{
background:#f1f1f1;
}

.sidebar hr{
margin:15px 0;
}

/* CONTENT */

.content{
flex:1;
padding:25px;
}

/* ANALYTICS */

.analytics{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
gap:20px;
margin-bottom:25px;
}

.stat{
background:white;
padding:25px;
border-radius:10px;
box-shadow:0 3px 10px rgba(0,0,0,0.12);
text-align:center;
}

.stat h2{
font-size:40px;
color:#1a6b3c;
margin-bottom:5px;
}

.stat p{
font-weight:bold;
color:#555;
}

/* MAP */

#map{
height:350px;
border-radius:10px;
margin-bottom:20px;
box-shadow:0 3px 10px rgba(0,0,0,0.15);
}

/* ACTIVITY */

.activity{
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 3px 10px rgba(0,0,0,0.12);
}

.activity h3{
color:#1a6b3c;
margin-bottom:10px;
}

.activity li{
padding:6px 0;
border-bottom:1px solid #eee;
font-size:14px;
}

/* SOS BUTTON */

#sosButton{
position:fixed;
bottom:30px;
right:30px;
background:#cc0000;
color:white;
font-size:1.6rem;
padding:12px 20px;
border:none;
border-radius:12px;
cursor:pointer;
z-index:9999;
box-shadow:0 4px 10px rgba(0,0,0,0.3);
}

#sosButton:active{
transform:scale(0.95);
}

/* FOOTER */

footer{
text-align:center;
padding:15px;
margin-top:30px;
font-size:0.85rem;
color:#777;
border-top:1px solid #ddd;
}

/* MOBILE */

@media(max-width:900px){

.layout{
flex-direction:column;
}

.sidebar{
width:100%;
border-right:none;
border-bottom:1px solid #ddd;
}

}

</style>

</head>

<body>

<header class="top-header">

<div class="logo-area">
<i class="fa-solid fa-truck-medical logo"></i>
<h1>SOS Emergency System</h1>
</div>

<div class="header-info">

<div class="info-box" id="datetime"></div>

<div class="info-box">
System Status: <b style="color:#a8d5ba;">Active</b>
</div>

</div>

</header>


<div class="layout">

<!-- SIDEBAR -->

<aside class="sidebar">

<h3>System Actions</h3>

<a href="log_call.html"><i class="fa-solid fa-phone"></i> Log Emergency Call</a>
<a href="create_incident.html"><i class="fa-solid fa-triangle-exclamation"></i> Create Incident</a>
<a href="add_casualty.html"><i class="fa-solid fa-user-injured"></i> Add Casualty</a>
<a href="assign_team.html"><i class="fa-solid fa-people-group"></i> Assign Rescue Team</a>
<a href="add_hospital.html"><i class="fa-solid fa-hospital"></i> Add Hospital</a>


<hr>

<h3>View Records</h3>


<a href="display_team.php">View Teams</a>
<a href="display_hospital.php">View Hospitals</a>
<a href="display_casualty.php">Casualty</a>
<a href="view_calls.php">View Calls</a>
<a href="display_incidents.php">View Incident</a>

</aside>


<main class="content">

<div class="analytics">

<div class="stat">
<h2><?php echo $calls; ?></h2>
<p>Emergency Calls</p>
</div>

<div class="stat">
<h2><?php echo $incidents; ?></h2>
<p>Total Incidents</p>
</div>

<div class="stat">
<h2><?php echo $casualties; ?></h2>
<p>Total Casualties</p>
</div>

<div class="stat">
<h2><?php echo $hospitals; ?></h2>
<p>Hospitals</p>
</div>

<div class="stat">
<h2><?php echo $teams; ?></h2>
<p>Rescue Teams</p>
</div>

</div>

<div id="map"></div>

<div class="activity">

<h3>System Activity</h3>

<ul id="activityLog">
<li>Dashboard Loaded</li>
</ul>

</div>

</main>

</div>


<button id="sosButton">🚨 SOS</button>


<footer>
© <?php echo date("Y"); ?> SOS Emergency System | DBMS Project
</footer>

<script>
/* DATE TIME */

function updateTime(){
document.getElementById("datetime").innerText =
new Date().toLocaleString();
}

setInterval(updateTime,1000);
updateTime();

let lastSOS = <?php echo json_encode($lastLocation); ?>;
/* MAP */

let map = L.map('map').setView([20.5937,78.9629],5);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
maxZoom:19
}).addTo(map);

let marker;
if(lastSOS){

let lat = lastSOS.Latitude;
let lng = lastSOS.Longitude;

map.setView([lat,lng],14);

L.marker([lat,lng])
.addTo(map)
.bindPopup("🚨 Last SOS Location")
.openPopup();

}

/* ACTIVITY LOG */

function logActivity(msg){

let log=document.getElementById("activityLog");

let li=document.createElement("li");

li.textContent=new Date().toLocaleTimeString()+" - "+msg;

log.prepend(li);

}


/* SOS BUTTON */

const sosButton=document.getElementById("sosButton");

let holdTimer;

function sendSOS(){

if(!navigator.geolocation){
alert("Geolocation not supported");
return;
}

navigator.geolocation.getCurrentPosition(function(position){

let lat = position.coords.latitude;
let lng = position.coords.longitude;


/* SHOW LOCATION ON MAP */

map.setView([lat,lng],14);

if(marker){
map.removeLayer(marker);
}

marker = L.marker([lat,lng]).addTo(map)
.bindPopup("🚨 SOS Location")
.openPopup();


/* SAVE TO DATABASE */

fetch("save_sos.php",{
method:"POST",
headers:{
"Content-Type":"application/json"
},
body:JSON.stringify({
latitude:lat,
longitude:lng
})
})

.then(res=>res.text())
.then(data=>{

alert("SOS Sent Successfully");

// automatically open add incident page
window.location.href = "create_incident.html";

/* UPDATE ACTIVITY */

logActivity("SOS Triggered at "+lat+", "+lng);

})
.catch(err=>{
console.error(err);
alert("Error sending SOS");
});

});
}


/* CLICK */

sosButton.addEventListener("click",sendSOS);


/* HOLD 3 SEC */

sosButton.addEventListener("mousedown",()=>holdTimer=setTimeout(sendSOS,3000));

sosButton.addEventListener("mouseup",()=>clearTimeout(holdTimer));

sosButton.addEventListener("mouseleave",()=>clearTimeout(holdTimer));

sosButton.addEventListener("touchstart",()=>holdTimer=setTimeout(sendSOS,3000));

sosButton.addEventListener("touchend",()=>clearTimeout(holdTimer));
</script>
</body>
</html>