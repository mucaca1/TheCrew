-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 19, 2019 at 01:35 AM
-- Server version: 5.7.25-0ubuntu0.18.04.2
-- PHP Version: 7.2.15-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `semestralne_zadanie`
--

-- --------------------------------------------------------

--
-- Table structure for table `mail_log`
--

CREATE TABLE `mail_log` (
  `ID` int(11) NOT NULL,
  `sent` date NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `subject` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `template_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mail_log`
--

INSERT INTO `mail_log` (`ID`, `sent`, `name`, `subject`, `template_id`) VALUES
(1, '2019-05-18', 'Feri mrkvicka', 'Testing 9', 1),
(2, '2019-05-18', 'Feri mrkvicka', 'Testing 9', 1),
(3, '2019-05-18', 'Feri mrkvicka', 'Testing 9', 1),
(4, '2019-05-18', 'Feri mrkvicka', '8', 1),
(5, '2019-05-18', 'Feri mrkvicka', '78', 1),
(6, '2019-05-18', 'Feri mrkvicka', '78', 1),
(7, '2019-05-18', 'Feri hruska', '78', 1),
(8, '2019-05-18', 'Feri mrkvicka', '', 1),
(9, '2019-05-18', 'Feri hruska', '', 1),
(10, '2019-05-18', 'Feri hruska', '', 1),
(11, '2019-05-18', 'Feri hruska', '', 1),
(12, '2019-05-18', 'Feri hruska', '', 1),
(13, '2019-05-18', 'Feri hruska', '', 1),
(14, '2019-05-18', 'Feri hruska', '', 1),
(15, '2019-05-18', 'Feri hruska', '', 1),
(16, '2019-05-18', 'Feri hruska', '', 1),
(17, '2019-05-18', 'Feri hruska', '', 1),
(18, '2019-05-18', 'Feri mrkvicka', 'sadasdasd', 1),
(19, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(20, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(21, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(22, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(23, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(24, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(25, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(26, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(27, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(28, '2019-05-18', 'Feri mrkvicka', 'sadasdasd', 1),
(29, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(30, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(31, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(32, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(33, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(34, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(35, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(36, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(37, '2019-05-18', 'Feri hruska', 'sadasdasd', 1),
(38, '2019-05-18', 'Feri mrkvicka', '6+546as5dsad', 1),
(39, '2019-05-18', 'Feri hruska', '6+546as5dsad', 1),
(40, '2019-05-18', 'Feri hruska', '6+546as5dsad', 1),
(41, '2019-05-18', 'Feri hruska', '6+546as5dsad', 1),
(42, '2019-05-18', 'Feri hruska', '6+546as5dsad', 1),
(43, '2019-05-18', 'Feri hruska', '6+546as5dsad', 1),
(44, '2019-05-18', 'Feri hruska', '6+546as5dsad', 1),
(45, '2019-05-18', 'Feri hruska', '6+546as5dsad', 1),
(46, '2019-05-18', 'Feri hruska', '6+546as5dsad', 1),
(47, '2019-05-18', 'Feri hruska', '6+546as5dsad', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mail_log`
--
ALTER TABLE `mail_log`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mail_log`
--
ALTER TABLE `mail_log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
