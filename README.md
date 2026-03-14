#  SOS Emergency Response System

A **DBMS-based Emergency Response Management System** that helps authorities manage **emergency calls, incidents, casualties, hospitals, and rescue teams** efficiently.
The system also provides a **real-time map interface** to visualize emergency locations using **Leaflet.js and OpenStreetMap**.

---

##  Project Overview

The **SOS Emergency System** is designed to simulate how real emergency services operate.
It allows administrators to:

* Log emergency calls
* Create incidents
* Track casualties
* Assign rescue teams
* Manage hospitals
* View emergency locations on an interactive map

When a user presses the **SOS button**, the system:

1. Detects the user’s **GPS location**
2. Saves the location in the **database**
3. Creates or links to an **incident record**
4. Displays the location on the **map dashboard**

---

##  Technologies Used

### Backend

* **PHP**
* **MySQL**

### Frontend

* **HTML5**
* **CSS3**
* **JavaScript**

### Map & Location

* **Leaflet.js**
* **OpenStreetMap**

### Development Environment

* **XAMPP / Apache Server**
* **phpMyAdmin**

---

##  Project Structure

```
SOS-Emergency-System
│
├── index.php
├── dash.php
├── view_dashboard.php
├── db_connect.php
│
├── add_casualty.html
├── create_incident.html
├── log_call.html
├── hospitals.html
│
├── api_add_casualty.php
├── api_add_hospital.php
├── api_assign_team.php
├── api_create_incident.php
├── api_log_call.php
│
├── api_incident_action.php
├── api_hospital_action.php
├── api_casualty_action.php
├── api_delete_hospital.php
│
├── save_incident.php
├── save_sos.php
│
├── display_team.php
├── display_hospital.php
├── display_casualty.php
├── display_incidents.php
├── view_calls.php
```

---

## Dashboard Features

The **Admin Dashboard** displays:

* 📞 Total Emergency Calls
* 🚨 Total Incidents
* 🧑‍⚕️ Casualty Count
* 🏥 Hospital Count
* 🚑 Rescue Team Count

It also includes an **interactive map** that shows:

* SOS locations
* Hospitals
* Rescue teams

---

##  SOS Feature

When the **SOS button** is clicked:

1. The system gets the **user’s GPS location**
2. The location is stored in the **`emergency_call` table**
3. A marker appears on the **map**
4. The activity is logged in the **system activity panel**

Example stored data:

| caller_phone | Latitude | Longitude | call_Timestamp |
| ------------ | -------- | --------- | -------------- |
| Unknown      | 18.5204  | 73.8567   | 2026-03-12     |

---

## 🗄 Database Tables

Main tables used in the system:

* **emergency_call**
* **incident**
* **casualty**
* **hospital**
* **rescue_team**

Example structure:

```
emergency_call
--------------
caller_phone
Latitude
Longitude
call_Timestamp
```

---

## ⚙ Installation Guide

### 1️ Install XAMPP

Download and install:
https://www.apachefriends.org

### 2️ Move Project

Place the project folder inside:

```
xampp/htdocs/
```

Example:

```
xampp/htdocs/sos_emergency_system
```

### 3️ Import Database

1. Open **phpMyAdmin**
2. Create a new database
3. Import the provided **SQL file**

### 4️ Run the Project

Open browser:

```
http://localhost/sos_emergency_system
```

---

##  Key Features

✔ Emergency call logging
✔ Incident management
✔ Casualty tracking
✔ Hospital management
✔ Rescue team assignment
✔ SOS location tracking
✔ Interactive map dashboard
✔ Activity logging system

---

##  Future Improvements

Possible upgrades:

* Automatic **nearest hospital detection**
* **Rescue team route navigation**
* **Real-time notifications**
* **Mobile app integration**
* **Live incident monitoring**

---

##  Author

Developed as a **DBMS Project** for academic purposes.

---
## License

This project is for **educational use only**.
