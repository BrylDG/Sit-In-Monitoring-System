-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 05:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sitinmonitoringsystem`
--
CREATE DATABASE IF NOT EXISTS `sitinmonitoringsystem` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sitinmonitoringsystem`;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL,
  `announcement_title` varchar(100) NOT NULL,
  `announcement_details` varchar(500) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Error reading data for table sitinmonitoringsystem.announcements: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `sitinmonitoringsystem`.`announcements`' at line 1

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedbackNo` int(11) NOT NULL,
  `idNo` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `feedback` varchar(500) NOT NULL,
  `lab` varchar(5) NOT NULL,
  `purpose` set('Java','C','C#','C++','Python','PHP','ASP.NET') NOT NULL,
  `explicit` set('yes','no') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Error reading data for table sitinmonitoringsystem.feedback: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `sitinmonitoringsystem`.`feedback`' at line 1

-- --------------------------------------------------------

--
-- Table structure for table `labschedules`
--

CREATE TABLE `labschedules` (
  `lab_no` int(11) NOT NULL,
  `open_time` time NOT NULL,
  `close_time` time NOT NULL,
  `status` set('Open','Closed') NOT NULL,
  `manually_closed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Error reading data for table sitinmonitoringsystem.labschedules: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `sitinmonitoringsystem`.`labschedules`' at line 1

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `idNo` varchar(10) NOT NULL,
  `sessions` int(11) NOT NULL DEFAULT 30,
  `points` int(11) NOT NULL,
  `TotalPoints` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Error reading data for table sitinmonitoringsystem.sessions: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `sitinmonitoringsystem`.`sessions`' at line 1

-- --------------------------------------------------------

--
-- Table structure for table `sitinhistory`
--

CREATE TABLE `sitinhistory` (
  `idNo` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `purpose` set('Java','C','C#','C++','Python','PHP','ASP.NET') NOT NULL,
  `lab` set('524','526','530') NOT NULL,
  `login` time NOT NULL,
  `logout` time DEFAULT curtime(),
  `date` date DEFAULT curdate(),
  `feedbackNo` int(11) DEFAULT NULL,
  `historyID` varchar(10) NOT NULL,
  `Duration` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Error reading data for table sitinmonitoringsystem.sitinhistory: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `sitinmonitoringsystem`.`sitinhistory`' at line 1

-- --------------------------------------------------------

--
-- Table structure for table `sitintable`
--

CREATE TABLE `sitintable` (
  `idNo` varchar(10) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `SitInTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `purpose` set('Java','C','C#','C++','Python','PHP','ASP.NET') NOT NULL,
  `lab` set('524','526','530') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Error reading data for table sitinmonitoringsystem.sitintable: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `sitinmonitoringsystem`.`sitintable`' at line 1

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `idNo` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `middleName` varchar(50) DEFAULT NULL,
  `course` varchar(10) NOT NULL,
  `yearLevel` varchar(1) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `profileImage` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Error reading data for table sitinmonitoringsystem.users: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `sitinmonitoringsystem`.`users`' at line 1

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedbackNo`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`idNo`);

--
-- Indexes for table `sitintable`
--
ALTER TABLE `sitintable`
  ADD PRIMARY KEY (`idNo`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idNo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
