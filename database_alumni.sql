-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2024 at 02:49 PM
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
-- Database: `database_alumni`
--

-- --------------------------------------------------------

--
-- Table structure for table `2024-2025`
--

CREATE TABLE `2024-2025` (
  `id` int(11) NOT NULL,
  `alumni_id` int(11) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `college` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  `year_graduated` year(4) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `personal_email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `2024-2025`
--

INSERT INTO `2024-2025` (`id`, `alumni_id`, `last_name`, `first_name`, `middle_name`, `college`, `department`, `section`, `year_graduated`, `contact_number`, `personal_email`) VALUES
(19, 1, 'Calda', 'Ivan Joshua', 'Mallo', 'CITCS', 'Bachelor of Science in Computer Science', 'BSCS 4G', '2024', '09124578870', 'ivan@gmail.com'),
(34, 4, 'Cunanan', 'Angela', 'Calda', 'CBA', 'Bachelor of Science in Business Administration - Major in Human Resource Development Management', 'BSBA 4A', '2024', '09124578865', 'ivan@gmail.com'),
(35, 3, 'Argame', 'Anne', 'Balonzo', 'CITCS', 'Bachelor of Science in Computer Science', 'BSCS 4G', '2024', '09124578862', 'Anne@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `2024-2025-ed`
--

CREATE TABLE `2024-2025-ed` (
  `id` int(11) NOT NULL,
  `alumni_id` int(11) DEFAULT NULL,
  `employment` varchar(50) DEFAULT NULL CHECK (`employment` in ('Employed','Self-Employed','Actively looking for a job','Never been employed')),
  `employment_status` varchar(255) DEFAULT NULL,
  `past_occupation` varchar(255) DEFAULT NULL,
  `present_occupation` varchar(255) DEFAULT NULL,
  `name_of_employer` varchar(255) DEFAULT NULL,
  `address_of_employer` varchar(255) DEFAULT NULL,
  `years_in_present_employer` int(11) DEFAULT NULL,
  `type_of_employer` varchar(255) DEFAULT NULL,
  `major_line_of_business` varchar(255) DEFAULT NULL,
  `job_related_to_program` varchar(3) DEFAULT NULL,
  `program_curriculum_relevant` varchar(3) DEFAULT NULL,
  `time_to_first_job` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `2024-2025-ed`
--

INSERT INTO `2024-2025-ed` (`id`, `alumni_id`, `employment`, `employment_status`, `past_occupation`, `present_occupation`, `name_of_employer`, `address_of_employer`, `years_in_present_employer`, `type_of_employer`, `major_line_of_business`, `job_related_to_program`, `program_curriculum_relevant`, `time_to_first_job`) VALUES
(11, 1, 'Actively looking for a job', '', '', '', '', '', 0, '', '', '', '', ''),
(21, 4, 'Self-Employed', '', '', '', '', '', 0, '', '', '', '', ''),
(22, 3, 'Employed', 'Regular/ Permanent', 'None', 'Encoder', 'Alorica', 'Alabang', 1, 'Private', 'Healthcare', 'Yes', 'No', '1 year');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `2024-2025`
--
ALTER TABLE `2024-2025`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alumni_id` (`alumni_id`);

--
-- Indexes for table `2024-2025-ed`
--
ALTER TABLE `2024-2025-ed`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alumni_id` (`alumni_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `2024-2025`
--
ALTER TABLE `2024-2025`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `2024-2025-ed`
--
ALTER TABLE `2024-2025-ed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `2024-2025-ed`
--
ALTER TABLE `2024-2025-ed`
  ADD CONSTRAINT `2024-2025-ed_ibfk_1` FOREIGN KEY (`alumni_id`) REFERENCES `2024-2025` (`alumni_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
