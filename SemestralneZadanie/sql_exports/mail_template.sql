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
-- Table structure for table `mail_template`
--

CREATE TABLE `mail_template` (
  `ID` int(11) NOT NULL,
  `TEMPLATE` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `TYPE` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mail_template`
--

INSERT INTO `mail_template` (`ID`, `TEMPLATE`, `TYPE`) VALUES
(1, 'Dobrý defgdfgdň, <b>na predmete Webové</b> technológie 2 budete mať k dispozícii vlastný virtuálny linux server, ktorý budete používať počas semestra, a na ktorom budete vypracovávať zadania. Prihlasovacie údaje k Vašemu serveru su uvedené nižšie. asdads<br>\r\nip adresa: {{verejnaIP}}<br>\r\nprihlasovacie meno: {{login}}<br>\r\nheslo: {{heslo}}a<br>\r\nVaše web stránky budú dostupné na: http:// {{verejnaIP}}:{{http}}<br>\r\nS pozdravom,<br>\r\n{{sender_name}}<br>\r\n{{sender_email}}', 'html'),
(2, 'KLLeobrý deň, <b>na predmete Webové</b> technológie 2 budete mať k dispozícii vlastný virtuálny linux server, ktorý budete používať počas semestra, a na ktorom budete vypracovávať zadania. Prihlasovacie údaje k Vašemu serveru su uvedené nižšie. <br>\r\nip adresa: {{verejnaIP}}<br>\r\nprihlasovacie meno: {{login}}<br>\r\nheslo: {{heslo}}<br>\r\nVaše web stránky budú dostupné na: http:// {{verejnaIP}}:{{http}}<br>\r\nS pozdravom,<br>\r\n{{sender_name}}<br>\r\n{{sender_email}}', 'html');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mail_template`
--
ALTER TABLE `mail_template`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mail_template`
--
ALTER TABLE `mail_template`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
