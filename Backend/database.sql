-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2026 at 05:52 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sos_audit_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `assigned_to`
--

CREATE TABLE `assigned_to` (
  `Incident_ID` int(11) NOT NULL,
  `Team_ID` int(11) NOT NULL,
  `Assigned_At` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assigned_to`
--

INSERT INTO `assigned_to` (`Incident_ID`, `Team_ID`, `Assigned_At`) VALUES
(1, 1, '2026-03-08 22:13:54'),
(1, 5, '2026-03-08 22:13:54'),
(2, 2, '2026-03-08 22:13:54'),
(2, 8, '2026-03-08 22:13:54'),
(3, 3, '2026-03-08 22:13:54'),
(3, 9, '2026-03-08 22:13:54'),
(4, 4, '2026-03-08 22:13:54'),
(4, 7, '2026-03-08 22:13:54'),
(5, 10, '2026-03-08 22:13:54');

-- --------------------------------------------------------

--
-- Table structure for table `casualty`
--

CREATE TABLE `casualty` (
  `Incident_ID` int(11) NOT NULL,
  `Sequence_No` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL DEFAULT 'Unknown',
  `Age` tinyint(4) DEFAULT NULL CHECK (`Age` between 0 and 120),
  `Triage_Color` enum('Red','Yellow','Green','Black') NOT NULL,
  `Admitted_Hospital_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `casualty`
--

INSERT INTO `casualty` (`Incident_ID`, `Sequence_No`, `Name`, `Age`, `Triage_Color`, `Admitted_Hospital_ID`) VALUES
(1, 1, 'Raj Sharma', 34, 'Red', 1),
(1, 2, 'Priya Mehta', 28, 'Yellow', 1),
(1, 3, 'Unknown', NULL, 'Red', 1),
(1, 4, 'Amit Singh', 45, 'Green', 1),
(1, 5, 'Unknown', NULL, 'Black', NULL),
(2, 1, 'Sunita Patil', 52, 'Red', 2),
(2, 2, 'Rahul Desai', 19, 'Yellow', 2),
(2, 3, 'Unknown', NULL, 'Green', 2),
(2, 4, 'Kavya Joshi', 7, 'Red', 2),
(3, 1, 'Vikram Patel', 40, 'Yellow', 3),
(3, 2, 'Neha Gupta', 25, 'Green', 3),
(3, 3, 'Unknown', NULL, 'Red', 3),
(4, 1, 'Ananya Rao', 33, 'Red', 4),
(4, 2, 'Suresh Kumar', 60, 'Black', NULL),
(4, 3, 'Unknown', NULL, 'Yellow', 4),
(4, 4, 'Divya Nair', 22, 'Red', 4),
(5, 1, 'Mohan Das', 48, 'Green', 5),
(5, 2, 'Unknown', NULL, 'Yellow', 5);

-- --------------------------------------------------------

--
-- Table structure for table `emergency_call`
--

CREATE TABLE `emergency_call` (
  `Call_ID` int(11) NOT NULL,
  `Caller_Phone` varchar(15) NOT NULL,
  `Latitude` decimal(9,6) NOT NULL,
  `Longitude` decimal(9,6) NOT NULL,
  `Call_Timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_call`
--

INSERT INTO `emergency_call` (`Call_ID`, `Caller_Phone`, `Latitude`, `Longitude`, `Call_Timestamp`) VALUES
(1, '9876543210', 19.076090, 72.877426, '2025-06-01 08:15:00'),
(2, '9123456780', 18.520430, 73.856743, '2025-06-01 09:30:00'),
(3, '9988776655', 28.704060, 77.102493, '2025-06-02 11:00:00'),
(4, '9871234560', 12.971599, 77.594566, '2025-06-02 13:45:00'),
(5, '9000011111', 22.572645, 88.363892, '2025-06-03 07:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `hospital`
--

CREATE TABLE `hospital` (
  `Hospital_ID` int(11) NOT NULL,
  `Name` varchar(150) NOT NULL,
  `Available_Beds` int(11) NOT NULL DEFAULT 0 CHECK (`Available_Beds` >= 0),
  `Street` varchar(200) NOT NULL,
  `City` varchar(100) NOT NULL,
  `Zip_Code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital`
--

INSERT INTO `hospital` (`Hospital_ID`, `Name`, `Available_Beds`, `Street`, `City`, `Zip_Code`) VALUES
(1, 'City General Hospital', 50, '12 MG Road', 'Mumbai', '400001'),
(2, 'Pune Relief Centre', 30, '45 FC Road', 'Pune', '411004'),
(3, 'Delhi Trauma Hospital', 20, '7 Connaught Place', 'Delhi', '110001'),
(4, 'Bangalore Care Hospital', 10, '88 Brigade Road', 'Bangalore', '560001'),
(5, 'Kolkata Medical Hub', 65, '3 Park Street', 'Kolkata', '700016');

-- --------------------------------------------------------

--
-- Table structure for table `incident`
--

CREATE TABLE `incident` (
  `Incident_ID` int(11) NOT NULL,
  `Trigger_Call_ID` int(11) NOT NULL,
  `Type` enum('Fire','Flood','Accident','Earthquake','Other') NOT NULL,
  `Severity` tinyint(4) NOT NULL CHECK (`Severity` between 1 and 5),
  `City` varchar(100) NOT NULL,
  `Reported_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Status` enum('Active','Controlled','Closed') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident`
--

INSERT INTO `incident` (`Incident_ID`, `Trigger_Call_ID`, `Type`, `Severity`, `City`, `Reported_At`, `Status`) VALUES
(1, 1, 'Fire', 5, 'Mumbai', '2026-03-08 22:13:54', 'Active'),
(2, 2, 'Flood', 4, 'Pune', '2026-03-08 22:13:54', 'Active'),
(3, 3, 'Accident', 3, 'Delhi', '2026-03-08 22:13:54', 'Controlled'),
(4, 4, 'Earthquake', 5, 'Bangalore', '2026-03-08 22:13:54', 'Active'),
(5, 5, 'Fire', 2, 'Kolkata', '2026-03-08 22:13:54', 'Closed');

-- --------------------------------------------------------

--
-- Table structure for table `rescue_team`
--

CREATE TABLE `rescue_team` (
  `Team_ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Status` enum('Available','Deployed','Off-Duty') NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rescue_team`
--

INSERT INTO `rescue_team` (`Team_ID`, `Name`, `Status`) VALUES
(1, 'Fire Squad Alpha', 'Deployed'),
(2, 'Flood Relief Beta', 'Deployed'),
(3, 'Medical Unit Gamma', 'Available'),
(4, 'Search & Rescue Delta', 'Deployed'),
(5, 'Hazmat Team Epsilon', 'Available'),
(6, 'Air Rescue Zeta', 'Off-Duty'),
(7, 'Urban Rescue Eta', 'Deployed'),
(8, 'Swift Water Theta', 'Deployed'),
(9, 'Trauma Unit Iota', 'Available'),
(10, 'Fire Squad Kappa', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `team_skills`
--

CREATE TABLE `team_skills` (
  `Team_ID` int(11) NOT NULL,
  `Skill` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_skills`
--

INSERT INTO `team_skills` (`Team_ID`, `Skill`) VALUES
(1, 'Evacuation'),
(1, 'Fire Suppression'),
(2, 'Boat Operations'),
(2, 'Flood Rescue'),
(2, 'Swimming'),
(3, 'CPR'),
(3, 'First Aid'),
(3, 'Triage'),
(4, 'Rope Rescue'),
(4, 'Rubble Search'),
(5, 'Decontamination'),
(5, 'Hazmat Handling'),
(6, 'Aerial Rescue'),
(6, 'Helicopter Ops'),
(7, 'Evacuation'),
(7, 'Structural Collapse'),
(8, 'Boat Operations'),
(8, 'Swift Water Rescue'),
(9, 'CPR'),
(9, 'First Aid'),
(9, 'Trauma Care'),
(10, 'Evacuation'),
(10, 'Fire Suppression');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assigned_to`
--
ALTER TABLE `assigned_to`
  ADD PRIMARY KEY (`Incident_ID`,`Team_ID`),
  ADD KEY `Team_ID` (`Team_ID`);

--
-- Indexes for table `casualty`
--
ALTER TABLE `casualty`
  ADD PRIMARY KEY (`Incident_ID`,`Sequence_No`),
  ADD KEY `Admitted_Hospital_ID` (`Admitted_Hospital_ID`);

--
-- Indexes for table `emergency_call`
--
ALTER TABLE `emergency_call`
  ADD PRIMARY KEY (`Call_ID`);

--
-- Indexes for table `hospital`
--
ALTER TABLE `hospital`
  ADD PRIMARY KEY (`Hospital_ID`);

--
-- Indexes for table `incident`
--
ALTER TABLE `incident`
  ADD PRIMARY KEY (`Incident_ID`),
  ADD KEY `Trigger_Call_ID` (`Trigger_Call_ID`);

--
-- Indexes for table `rescue_team`
--
ALTER TABLE `rescue_team`
  ADD PRIMARY KEY (`Team_ID`);

--
-- Indexes for table `team_skills`
--
ALTER TABLE `team_skills`
  ADD PRIMARY KEY (`Team_ID`,`Skill`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `emergency_call`
--
ALTER TABLE `emergency_call`
  MODIFY `Call_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hospital`
--
ALTER TABLE `hospital`
  MODIFY `Hospital_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `incident`
--
ALTER TABLE `incident`
  MODIFY `Incident_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rescue_team`
--
ALTER TABLE `rescue_team`
  MODIFY `Team_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assigned_to`
--
ALTER TABLE `assigned_to`
  ADD CONSTRAINT `assigned_to_ibfk_1` FOREIGN KEY (`Incident_ID`) REFERENCES `incident` (`Incident_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assigned_to_ibfk_2` FOREIGN KEY (`Team_ID`) REFERENCES `rescue_team` (`Team_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `casualty`
--
ALTER TABLE `casualty`
  ADD CONSTRAINT `casualty_ibfk_1` FOREIGN KEY (`Incident_ID`) REFERENCES `incident` (`Incident_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `casualty_ibfk_2` FOREIGN KEY (`Admitted_Hospital_ID`) REFERENCES `hospital` (`Hospital_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `incident`
--
ALTER TABLE `incident`
  ADD CONSTRAINT `incident_ibfk_1` FOREIGN KEY (`Trigger_Call_ID`) REFERENCES `emergency_call` (`Call_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `team_skills`
--
ALTER TABLE `team_skills`
  ADD CONSTRAINT `team_skills_ibfk_1` FOREIGN KEY (`Team_ID`) REFERENCES `rescue_team` (`Team_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
