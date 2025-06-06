-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 06, 2025 at 03:40 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `courseId` int(11) NOT NULL,
  `course_name` text NOT NULL,
  `fee` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`courseId`, `course_name`, `fee`) VALUES
(1, 'Computer Science', 30000),
(2, 'Business studies', 26400),
(3, 'Computer information system', 27800),
(4, 'Acturial Science', 54500);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `deptId` int(11) NOT NULL,
  `deptName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`deptId`, `deptName`) VALUES
(5, 'Biology'),
(6, 'Business Administration'),
(4, 'Chemistry'),
(1, 'Computer Science'),
(7, 'Education'),
(8, 'Engineering'),
(2, 'Mathematics'),
(3, 'Physics');

-- --------------------------------------------------------

--
-- Table structure for table `lecturer`
--

CREATE TABLE `lecturer` (
  `lecturerId` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `sname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `department` int(11) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lecturer`
--

INSERT INTO `lecturer` (`lecturerId`, `fname`, `sname`, `email`, `phone`, `department`, `gender`, `password`, `created_at`) VALUES
(1, 'henry', 'njue', 'njue@gmail.com', '0757641234', 1, 'male', '$2y$10$mu5ixrOTwEfuAEfvD4L9iuDMcyDHAILw0Nrkn3trzx2vHIvR/jX02', '2025-06-05 09:05:36'),
(2, 'Cate', 'Kari', 'kari@gmail.com', '0742735159', 8, 'female', '$2y$10$VXLP9yTAzIdtacaigDTFwusaSZXXvxpm70yo1cLPlwGG2y1A.KsIq', '2025-06-05 09:10:37'),
(3, 'David', 'Kaje', 'davidkaje@gmail.com', '0742735156', 1, 'male', '$2y$10$dyyOo4VrSmmXxG0WzKy0xeSrhRSPKXA3FUc.8vj5tpJS4z8NDD/NO', '2025-06-05 19:26:16'),
(4, 'Lucy', 'Kihara', 'lucykihara@gmail.com', '0742735153', 6, 'female', '$2y$10$C8Dg2Jk2FAre907NYCwdqu1J2R67YkwtAjvHeHmfZ.FeMuJgM2yRq', '2025-06-05 19:27:07');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `paymentId` int(11) NOT NULL,
  `reg_no` varchar(100) NOT NULL,
  `amount_paid` int(10) NOT NULL DEFAULT 0,
  `balance` int(10) NOT NULL DEFAULT 0,
  `code` varchar(50) NOT NULL,
  `mode_payment` varchar(10) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `payStatus` varchar(50) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`paymentId`, `reg_no`, `amount_paid`, `balance`, `code`, `mode_payment`, `date`, `payStatus`) VALUES
(1, 'S2024-1', 5000, 25000, 'js7sdfh45d', 'Mpesa', '2024-10-01', 'approved'),
(2, 'S2024-1', 1000, 29000, 'j3458sdj34', 'Mpesa', '2024-10-01', 'approved'),
(3, 'S2024-1', 3000, 27000, 'H4MFD82JFS', 'Mpesa', '2024-11-20', 'approved'),
(4, 'S2024-2', 2900, 23500, 'IWE82JASCA', 'Mpesa', '2024-12-17', 'approved'),
(5, 'S2024-4', 12000, 14400, 'JD992B9ZXK', 'Bank', '2025-01-06', 'approved'),
(6, 'S2024-1', 1000, 20000, 'WYD6SV37S', 'Bank', '2025-06-05', 'approved'),
(7, 'S2024-4', 14400, 0, 'YE6V37CB3', 'Cash', '2025-06-05', 'approved'),
(8, 'S2025-1', 20000, 10000, 'G73S63VS8', 'Cash', '2025-06-05', 'approved'),
(9, 'S2025-1', 3000, 7000, 'F5V6G3D3XV', 'Mpesa', '2025-06-05', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `semesterId` int(11) NOT NULL,
  `sem_name` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`semesterId`, `sem_name`, `start_date`, `end_date`) VALUES
(1, 'Semester one', '2024-09-02', '2024-12-11'),
(2, 'Semester two', '2025-01-06', '2025-04-10');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `studentId` int(11) NOT NULL,
  `courseId` int(11) NOT NULL,
  `reg_no` varchar(255) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `sname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `county` varchar(100) DEFAULT NULL,
  `subcounty` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `regDate` datetime NOT NULL DEFAULT current_timestamp(),
  `password` char(40) NOT NULL,
  `studentStatus` enum('approved','pending','rejected') NOT NULL DEFAULT 'approved',
  `status` varchar(10) NOT NULL DEFAULT 'Student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`studentId`, `courseId`, `reg_no`, `fname`, `sname`, `email`, `phone`, `county`, `subcounty`, `gender`, `regDate`, `password`, `studentStatus`, `status`) VALUES
(1, 1, 'S2024-1', 'Henry', 'Muchiri', 'henry@gmail.com', '0742735159', 'Kirinyaga', 'Mwea East', 'Male', '2024-09-14 02:25:35', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'approved', 'Student'),
(2, 2, 'S2024-2', 'Njeri', 'Val', 'valnjeri@gmail.com', '0754872615', 'Nakuru', 'Naivasha', 'Female', '2024-09-14 15:48:36', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'approved', 'Student'),
(3, 3, 'S2024-3', 'Purity', 'Siaka', 'puritynsiaka@gmail.com', '0757807872', 'Kajiado', 'Kajiado Central', 'Female', '2024-09-18 19:42:45', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'approved', 'Student'),
(4, 2, 'S2024-4', 'Joy', 'Njue', 'joynjue@gmail.com', '0783647287', 'Kirinyaga', 'Mwea West', 'Female', '2024-09-18 19:45:52', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'approved', 'Student'),
(5, 1, 'S2024-5', 'Calibry', 'Mark', 'calibrymark@gmail.com', '0746712456', 'Kiambu', 'Kikuyu', 'Male', '2024-09-18 19:46:24', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'approved', 'Student'),
(6, 4, 'S2024-6', 'Kimani', 'Kamau', 'kimanikamau@gmail.com', '0742735151', 'Kirinyaga', 'Kirinyaga West', 'Female', '2024-09-18 19:53:54', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'approved', 'Student'),
(7, 2, 'S2024-7', 'Antony', 'Mugo', 'antonymugo@gmail.com', '0748726452', 'Kisii', 'Nyamache', 'Female', '2024-10-01 19:20:54', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'approved', 'Student'),
(8, 1, 'S2025-1', 'hello', 'world', 'njue@gmail.com', '0712345673', 'Baringo', 'Baringo Central', 'Male', '2025-06-05 11:45:11', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'approved', 'Student'),
(9, 4, 'S2025-2', 'Claire', 'Wakuthii', 'clairewakuthii@gmail.com', '0702755804', 'Kirinyaga', 'Gichugu', 'Female', '2025-06-05 22:33:04', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'approved', 'Student');

-- --------------------------------------------------------

--
-- Table structure for table `userlogin`
--

CREATE TABLE `userlogin` (
  `id` int(11) NOT NULL DEFAULT 0,
  `staffid` varchar(20) NOT NULL,
  `status` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `othernames` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `userStatus` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userlogin`
--

INSERT INTO `userlogin` (`id`, `staffid`, `status`, `surname`, `othernames`, `password`, `userStatus`) VALUES
(0, 'Admin100', 'Admin', 'Henry', 'Njue', '8cb2237d0679ca88db6464eac60da96345513964', 'active'),
(0, 'finance101', 'Finance', 'Cate', 'Kari', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`courseId`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`deptId`),
  ADD UNIQUE KEY `deptName` (`deptName`);

--
-- Indexes for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD PRIMARY KEY (`lecturerId`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `department` (`department`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`paymentId`),
  ADD KEY `reg_no` (`reg_no`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`semesterId`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`studentId`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `reg_no` (`reg_no`),
  ADD KEY `courseId` (`courseId`);

--
-- Indexes for table `userlogin`
--
ALTER TABLE `userlogin`
  ADD PRIMARY KEY (`staffid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `courseId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `deptId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lecturer`
--
ALTER TABLE `lecturer`
  MODIFY `lecturerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `paymentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `semester`
--
ALTER TABLE `semester`
  MODIFY `semesterId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `studentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD CONSTRAINT `lecturer_ibfk_1` FOREIGN KEY (`department`) REFERENCES `department` (`deptId`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`reg_no`) REFERENCES `student` (`reg_no`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`courseId`) REFERENCES `courses` (`courseId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
