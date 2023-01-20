-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2023 at 06:30 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pojisteni_plna_oop`
--

-- --------------------------------------------------------

--
-- Table structure for table `pojistenci`
--

CREATE TABLE `pojistenci` (
  `pojistenci_id` int(11) NOT NULL,
  `jmeno` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `prijmeni` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `telefon` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `ulice_cp` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `mesto` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `psc` varchar(6) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `pojistenci`
--

INSERT INTO `pojistenci` (`pojistenci_id`, `jmeno`, `prijmeni`, `email`, `telefon`, `ulice_cp`, `mesto`, `psc`) VALUES
(10, 'Josef', 'Nový', 'jnovy@atlas.com', '725 458 111', 'Hlavní 145/9', 'Praha 9', '999 91'),
(52, 'Johan', 'Novák', 'jannovak88@seznam.cz', '731 584 972', 'Ve Svahu 3', 'Praha 1', '100 01'),
(80, 'Petr', 'Mazanec', 'pm@email.com', '123 456 789', 'Pražská 123', 'Ostrava', '400 00'),
(82, 'Petra', 'Mazancová', 'pmova@email.com', '789 789 789', 'Na konci světa 1', 'Ostrava Hrabůvka', '560 00'),
(84, 'Petro', 'Mazanec', 'pm@email.com', '789 789 789', 'Pražská 123', 'Ostrava', '450 00'),
(85, 'Petra', 'Nováková', 'pnovakova@seznam.cz', '987 654 432', 'Ve Svahu 23', 'Brno', '300 00'),
(91, 'Honza', 'Král', 'kral@seznam.cz', '456 456 451', 'Hradčanská 65', 'Ostrava Hrabůvka', '110 00'),
(92, 'Filipa', 'Citrón', 'citron@zelenina.cz', '123 543 321', 'Tržiště 190', 'Brno', '345 00'),
(94, 'Petr', 'Bezruč', 'kopac@ostravak.info', '731 456 316', 'Důlní 99', 'Ostrava - Hrabůvka', '400 02');

-- --------------------------------------------------------

--
-- Table structure for table `pojisteni`
--

CREATE TABLE `pojisteni` (
  `pojisteni_id` int(11) NOT NULL,
  `poj_produkt` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `poj_castka` int(11) NOT NULL,
  `predmet_poj` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `platnost_od` date NOT NULL,
  `platnost_do` date NOT NULL,
  `pojistenec_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `pojisteni`
--

INSERT INTO `pojisteni` (`pojisteni_id`, `poj_produkt`, `poj_castka`, `predmet_poj`, `platnost_od`, `platnost_do`, `pojistenec_id`) VALUES
(2, 'Havarijní pojištění', 500000, 'Automobil Golf', '2022-01-01', '2022-12-31', 10),
(3, 'Pojištění zákonné odpovědnosti z provozu motorových vozidel', 50000001, 'Automobil', '2023-01-01', '2023-12-31', 10),
(4, 'Pojištění nemovitosti', 1000000, 'Byt', '2023-01-01', '2023-12-31', 10),
(6, 'Pojištění nemovitosti', 111111, 'Garáž', '2021-01-01', '2023-05-30', 10),
(7, 'Pojištění domácnosti', 333333, 'Domácnost 2', '2021-03-01', '2023-03-01', 10),
(10, 'Havarijní pojištění', 4444445, 'Domácnost', '2020-05-05', '2024-05-04', 52),
(32, 'Pojištění zákonné odpovědnosti z provozu motorových vozidel', 309, 'Trakař', '2021-03-03', '2023-03-02', 82),
(33, 'Pojištění domácnosti', 100001, 'Krámy', '2021-02-01', '2022-01-31', 85),
(34, 'Pojištění nemovitosti', 330000, 'Auto Škoda', '2021-02-01', '2023-01-31', 84);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pojistenci`
--
ALTER TABLE `pojistenci`
  ADD PRIMARY KEY (`pojistenci_id`);

--
-- Indexes for table `pojisteni`
--
ALTER TABLE `pojisteni`
  ADD PRIMARY KEY (`pojisteni_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pojistenci`
--
ALTER TABLE `pojistenci`
  MODIFY `pojistenci_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `pojisteni`
--
ALTER TABLE `pojisteni`
  MODIFY `pojisteni_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
