-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 16, 2022 at 05:33 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `choreographiclineage_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_features`
--

CREATE TABLE `admin_features` (
  `feature_id` int(11) NOT NULL,
  `feature_name` varchar(50) NOT NULL,
  `feature_enabled` tinyint(1) NOT NULL,
  `feature_updated_by` varchar(50) NOT NULL,
  `feature_updated_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_features`
--

INSERT INTO `admin_features` (`feature_id`, `feature_name`, `feature_enabled`, `feature_updated_by`, `feature_updated_date`) VALUES
(1, 'Year of Birth (Form)', 0, 'Gopi Chand Pendyala', '2022-11-15'),
(2, 'Year of Birth (User Profile)', 1, 'Gopi Chand Pendyala', '2022-11-09'),
(3, 'Artist Type (Form)', 1, 'Gopi Chand Pendyala', '2022-11-09'),
(4, 'Tutorial (Popup)', 0, 'Gopi Chand Pendyala', '2022-11-15'),
(5, 'Genres (Form)', 1, 'Gopi Chand Pendyala', '2022-11-09'),
(6, 'Genres (User Profile)', 1, 'Gopi Chand Pendyala', '2022-11-09'),
(7, 'Gender (Form)', 1, 'Gopi Chand Pendyala', '2022-11-09'),
(8, 'Ethnicity (Form)', 1, 'Gopi Chand Pendyala', '2022-11-09'),
(9, 'Location (Form)', 1, 'Gopi Chand Pendyala', '2022-11-09'),
(10, 'Education (Form)', 1, 'Gopi Chand Pendyala', '2022-11-09'),
(11, 'Biography (Form)', 1, 'Gopi Chand Pendyala', '2022-11-09'),
(12, 'Biography (User Profile)', 1, 'Gopi Chand Pendyala', '2022-11-09'),
(13, 'Headshot (Form)', 1, 'Gopi Chand Pendyala', '2022-11-09'),
(14, 'Headshot (User Profile)', 1, 'Gopi Chand Pendyala', '2022-11-09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_features`
--
ALTER TABLE `admin_features`
  ADD PRIMARY KEY (`feature_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
