-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 13, 2020 at 08:58 PM
-- Server version: 5.7.24
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `id` int(11) NOT NULL,
  `id_user_id` int(11) NOT NULL,
  `id_car_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `price` double NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `returned` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `id` int(11) NOT NULL,
  `id_owner_id` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `datasheet` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `amount` double NOT NULL,
  `rent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `car`
--

INSERT INTO `car` (`id`, `id_owner_id`, `type`, `datasheet`, `amount`, `rent`, `image`, `quantity`) VALUES
(1, 4, 'Peugeot 2008', '{\"category\":\"4x4, SUV, Crossover\",\"fuel\":\"Essence\",\"engine\":\"Thermique\",\"shift\":\"Manuelle\",\"nb_portes\":\"5 portes\"}', 120, 'disponible', '2008.jpeg', 8),
(2, 4, 'Renault Clio 5', '{\"category\":\"Citadine\",\"fuel\":\"Diesel\",\"engine\":\"Thermique\",\"shift\":\"Manuelle\",\"nb_portes\":\"5 portes\"}', 100, 'disponible', 'clio5.jpeg', 12),
(3, 4, 'Volkswagen Golf 7', '{\"category\":\"Berline\",\"fuel\":\"Essence\",\"engine\":\"Thermique\",\"shift\":\"Automatique\",\"nb_portes\":\"5 portes\"}', 110, 'disponible', 'volkswagen-golf-7.jpeg', 6);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Admin', 'admin@rentcar.com', '$2y$13$YYseT2BsUH9745hHorvwTeufg/P6UOT46V/uwRFKDEnMtuavC7uRO', 'ROLE_ADMIN'),
(2, 'Hubert Pichet', 'hubert.pichet@gmail.com', '$2y$13$yy4V7E2cjmZZXpSLilfv7uMHHs6a6v43bIuZSwy2UshF4OdjFauv6', 'ROLE_CLIENT'),
(3, 'Easy Rent', 'easyrent@easyrent.com', '$2y$13$Rsd2.JuG3IOiVXxrtM1a3OA2rMHhOS7SBsDaGZGML8ishREbep.nW', 'ROLE_RENTER'),
(4, 'Sixt', 'sixt@sixt.com', '$2y$13$hYDwhipyPK4I8iffsSQLzu9VSYL.oZvqsaaOJzjt/xaPL93f8h6sy', 'ROLE_RENTER'),
(5, 'Hertz', 'hertz@hertz.com', '$2y$13$eo5X7iqP7H/HkRrANGXLZOszb03coeo8il6ffo9yD/9P/FsRiJo9y', 'ROLE_RENTER'),
(6, 'Jerome Aurore', 'jerome.aurore@hotmail.com', '$2y$13$i0kTXAFNDHxAdOiknKVnmuGX.FcjwGHo9ajP.JMg9wKwd9.2wgJ0i', 'ROLE_CLIENT'),
(7, 'Lena Selim', 'selimlena@gmail.com', '$2y$13$fREKb5oUSUZ11w8Guj8AwuVJbbKbglLfIp5yPOnOqiHYTI4rlwfjS', 'ROLE_CLIENT');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_EC224CAA79F37AE5` (`id_user_id`),
  ADD KEY `IDX_EC224CAAE5F14372` (`id_car_id`);

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_773DE69D2EE78D6C` (`id_owner_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526CC3C6F69F` (`car_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `car`
--
ALTER TABLE `car`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `billing`
--
ALTER TABLE `billing`
  ADD CONSTRAINT `FK_EC224CAA79F37AE5` FOREIGN KEY (`id_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_EC224CAAE5F14372` FOREIGN KEY (`id_car_id`) REFERENCES `car` (`id`);

--
-- Constraints for table `car`
--
ALTER TABLE `car`
  ADD CONSTRAINT `FK_773DE69D2EE78D6C` FOREIGN KEY (`id_owner_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526CC3C6F69F` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
