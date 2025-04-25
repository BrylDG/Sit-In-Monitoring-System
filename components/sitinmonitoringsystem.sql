-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2025 at 06:02 PM
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

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `announcement_title`, `announcement_details`, `created_on`) VALUES
(13, 'Trial', 'Trial', '2025-04-11 03:00:11');

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

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedbackNo`, `idNo`, `name`, `feedback`, `lab`, `purpose`, `explicit`) VALUES
(563409355, '12345678', 'Kobe A Amaro', 'Bogo kaau ang pc sa lab 526', '526', 'Python', 'yes'),
(641156572, '20949194', 'Bryl M Gorgonio', 'bogo', '530', 'PHP', 'yes'),
(944139097, '23615172', 'Christine A Alesna', 'wow', '530', 'Java', 'no'),
(958054137, '20949194', 'Bryl M Gorgonio', 'wowowow', '526', 'C#', 'no');

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
('12345678', 30),
('20949194', 30),
('23615172', 30);

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

--
-- Dumping data for table `sitinhistory`
--

INSERT INTO `sitinhistory` (`idNo`, `name`, `purpose`, `lab`, `login`, `logout`, `date`, `feedbackNo`, `historyID`, `Duration`) VALUES
('20949194', 'Bryl M Gorgonio', 'C#', '526', '10:00:48', '10:25:03', '2025-04-11', 958054137, '33239527', '00:24:15'),
('12345678', 'Kobe A Amaro', 'Python', '526', '10:31:43', '10:42:20', '2025-04-11', 563409355, '88595783', '00:10:37'),
('20949194', 'Bryl M Gorgonio', 'PHP', '530', '10:32:10', '10:42:25', '2025-04-11', 641156572, '18943341', '00:10:15'),
('23615172', 'Christine A Alesna', 'Java', '530', '10:31:53', '10:43:06', '2025-04-11', 944139097, '35598391', '00:11:13'),
('20949194', 'Bryl M Gorgonio', 'C++', '', '11:04:00', '11:04:05', '2025-04-11', NULL, '73059548', '00:00:05'),
('12345678', 'Kobe A Amaro', 'PHP', '526', '11:04:38', '11:04:42', '2025-04-11', NULL, '46617010', '00:00:04'),
('20949194', 'Bryl M Gorgonio', 'PHP', '526', '11:04:27', '11:04:44', '2025-04-11', NULL, '40319154', '00:00:17');

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
('12345678', 'Amaro', 'Kobe', 'A', 'BSCS', '1', 'kobe@gmail.com', 'kobe', '123', 'image.png', '2025-04-11 02:31:26', 'student'),
('20949194', 'Gorgonio', 'Bryl', 'M', 'BSIT', '1', 'brylgorgonio@gmail.com', 'bryl', '123', 'image.png', '2025-03-28 01:40:14', 'student'),
('23615172', 'Alesna', 'Christine', 'A', 'BSIT', '2', 'christine@gmail.com', 'christine', '123', 'image.png', '2025-04-11 02:31:04', 'student');

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
