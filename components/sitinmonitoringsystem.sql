-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2025 at 10:00 PM
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

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `announcement_title`, `announcement_details`, `created_on`) VALUES
(1, 'trial', 'trial', '2025-03-20 10:25:20'),
(2, 'trial', 'trial', '2025-03-20 10:25:21'),
(3, 'trial', 'trial', '2025-03-20 10:25:22'),
(4, 'trial', 'trial', '2025-03-20 10:25:22'),
(5, 'trial', 'trial', '2025-03-20 10:26:46'),
(6, 'trial', 'trial', '2025-03-20 10:26:47'),
(7, 'tiral announcement', 'trial', '2025-03-20 10:32:32'),
(8, 'Trial announcement 2', 'WOWOWOWOW!', '2025-03-20 11:41:22'),
(9, 'christine gwapa', 'awwaawaw', '2025-03-20 11:41:53'),
(10, 'waw', 'aww', '2025-03-20 17:53:39');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedbackNo` int(11) NOT NULL,
  `idNo` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `feedback` varchar(500) NOT NULL,
  `lab` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedbackNo`, `idNo`, `name`, `feedback`, `lab`) VALUES
(0, '21956124', 'undefined', '123', 'undef'),
(36818903, '21956124', 'undefined', 'waw', 'undef'),
(68632877, '21956124', 'undefined', 'awaw', 'undef');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `idNo` varchar(10) NOT NULL,
  `sessions` int(11) NOT NULL DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`idNo`, `sessions`) VALUES
('20949194', 27),
('21950114', 27);

-- --------------------------------------------------------

--
-- Table structure for table `sitinhistory`
--

CREATE TABLE `sitinhistory` (
  `idNo` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `lab` varchar(5) NOT NULL,
  `login` time NOT NULL,
  `logout` time DEFAULT curtime(),
  `date` date DEFAULT curdate(),
  `feedbackNo` int(11) DEFAULT NULL,
  `historyID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sitinhistory`
--

INSERT INTO `sitinhistory` (`idNo`, `name`, `purpose`, `lab`, `login`, `logout`, `date`, `feedbackNo`, `historyID`) VALUES
('21956124', 'Clifford A Alferez', 'c#', '123', '04:52:15', '04:52:32', '2025-03-21', 36818903, '44344518');

-- --------------------------------------------------------

--
-- Table structure for table `sitintable`
--

CREATE TABLE `sitintable` (
  `idNo` varchar(10) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `SitInTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `purpose` varchar(100) NOT NULL,
  `lab` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sitintable`
--

INSERT INTO `sitintable` (`idNo`, `student_name`, `SitInTime`, `purpose`, `lab`) VALUES
('21956124', 'Clifford A Alferez', '2025-03-20 20:59:29', 'c#', '');

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

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`idNo`, `lastName`, `firstName`, `middleName`, `course`, `yearLevel`, `email`, `username`, `password`, `profileImage`, `created_at`, `role`) VALUES
('00000000', 'admin', 'admin', 'admin', 'admin', '0', 'admin@gmail.com', 'admin', 'admin', 'admin.png', '2025-03-20 08:13:33', 'admin'),
('20949194', 'Gorgonio', 'Bryl', 'Mejeca', 'BSIT', '2', 'bryl@gmail.com', 'bryl', '123', 'image.png', '2025-03-20 17:35:12', 'student'),
('21950114', 'Sebios', 'Jade', 'M', 'BSIT', '2', 'jade@gmail.com', 'jade', '123', 'image.png', '2025-03-20 17:32:17', 'student'),
('21956124', 'Alferez', 'Clifford', 'A', 'BSIT', '2', 'clifford@gmail.com', 'clifford', '123', 'clifford.png', '2025-03-19 08:13:31', 'student'),
('22613871', 'Alesna', 'Christine', 'A', 'BSIT', '2', 'christine@gmail.com', 'christine', '123', 'image.png', '2025-03-19 08:17:33', 'student'),
('23615172', 'Amaro', 'Kobe', 'Bryan', 'BSIT', '1', 'kobe@gmail.com', 'kobe', '123', 'image.png', '2025-03-19 20:03:15', 'student');

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
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
