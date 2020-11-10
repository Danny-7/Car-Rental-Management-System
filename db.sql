-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.24 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for project
DROP DATABASE IF EXISTS `project`;
CREATE DATABASE IF NOT EXISTS `project` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `project`;

-- Dumping structure for table project.billing
DROP TABLE IF EXISTS `billing`;
CREATE TABLE IF NOT EXISTS `billing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_id` int(11) NOT NULL,
  `id_car_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `price` double NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `returned` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_EC224CAA79F37AE5` (`id_user_id`),
  KEY `IDX_EC224CAAE5F14372` (`id_car_id`),
  CONSTRAINT `FK_EC224CAA79F37AE5` FOREIGN KEY (`id_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_EC224CAAE5F14372` FOREIGN KEY (`id_car_id`) REFERENCES `car` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table project.billing: ~22 rows (approximately)
DELETE FROM `billing`;
/*!40000 ALTER TABLE `billing` DISABLE KEYS */;
INSERT INTO `billing` (`id`, `id_user_id`, `id_car_id`, `start_date`, `end_date`, `price`, `paid`, `returned`) VALUES
	(47, 7, 8, '2020-11-05', NULL, 125, 0, 1),
	(48, 7, 7, '2020-11-05', '2020-11-14', 250, 1, 1),
	(49, 7, 4, '2020-11-04', '2020-11-06', 400, 1, 1),
	(50, 7, 3, '2020-11-05', NULL, 300, 0, 0),
	(51, 7, 8, '2020-11-05', '2020-11-07', 125, 1, 1),
	(52, 7, 8, '2020-11-06', '2020-11-07', 125, 1, 1),
	(53, 6, 4, '2020-11-08', '2020-11-11', 400, 1, 1),
	(54, 6, 4, '2020-11-08', '2020-11-11', 400, 1, 1),
	(55, 6, 7, '2020-11-14', NULL, 250, 0, 0),
	(56, 6, 8, '2020-11-13', '2020-11-20', 125, 1, 1),
	(57, 6, 8, '2020-11-13', '2020-11-20', 125, 1, 1),
	(58, 6, 5, '2020-11-11', '2020-11-13', 600, 1, 1),
	(59, 6, 7, '2020-11-08', '2020-11-09', 250, 1, 1),
	(60, 6, 7, '2020-11-08', '2020-11-09', 250, 1, 1),
	(61, 6, 7, '2020-11-14', '2020-11-21', 250, 1, 1),
	(62, 6, 1, '2020-11-08', '2020-11-10', 300, 1, 1),
	(63, 6, 1, '2020-11-08', '2020-11-10', 300, 1, 1),
	(64, 6, 1, '2020-11-08', '2020-11-10', 300, 1, 1),
	(65, 6, 3, '2020-11-08', '2020-11-09', 270, 1, 1),
	(66, 7, 1, '2020-11-10', '2020-11-11', 300, 1, 1),
	(67, 7, 1, '2020-11-10', '2020-11-11', 300, 1, 1),
	(68, 7, 4, '2020-11-10', '2020-11-12', 400, 1, 1);
/*!40000 ALTER TABLE `billing` ENABLE KEYS */;

-- Dumping structure for table project.car
DROP TABLE IF EXISTS `car`;
CREATE TABLE IF NOT EXISTS `car` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_owner_id` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `datasheet` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `amount` double NOT NULL,
  `rent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_773DE69D2EE78D6C` (`id_owner_id`),
  CONSTRAINT `FK_773DE69D2EE78D6C` FOREIGN KEY (`id_owner_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table project.car: ~8 rows (approximately)
DELETE FROM `car`;
/*!40000 ALTER TABLE `car` DISABLE KEYS */;
INSERT INTO `car` (`id`, `id_owner_id`, `type`, `datasheet`, `amount`, `rent`, `image`, `quantity`) VALUES
	(1, 1, 'Mercedes Classe A', '{"category":"Citadine","fuel":"Essence","engine":"Thermique","shift":"Automatique","nb_portes":"5 portes"}', 300, 'disponible', 'mercedesA.png', 5),
	(2, 1, 'Clio 4', '{"category":"Citadine","fuel":"Diesel","engine":"Thermique","shift":"Manuelle","nb_portes":"5 portes"}', 120, 'disponible', 'S7-modele-renault-clio-4.jpeg', 3),
	(3, 1, 'Audi A3 berline', '{"category":"Berline","fuel":"Essence","engine":"Thermique","shift":"Manuelle","nb_portes":"5 portes"}', 300, 'disponible', 'audi-a3.jpeg', 11),
	(4, 3, 'Mercedes Classe C', '{"category":"Berline","fuel":"Essence","engine":"Thermique","shift":"Automatique","nb_portes":"5 portes"}', 400, 'disponible', 'mercedes-benz-c-class.jpeg', 6),
	(5, 3, 'Porsche Macan  Turbo S 2020', '{"category":"4x4, SUV, Crossover","fuel":"Essence","engine":"Thermique","shift":"Automatique","nb_portes":"5 portes"}', 600, 'disponible', 'macan.png', 9),
	(6, 3, 'Porsche Cayenne 2018', '{"category":"4x4, SUV, Crossover","fuel":"Essence","engine":"Thermique","shift":"Automatique","nb_portes":"5 portes"}', 700, 'disponible', 'cayennejpg.jpeg', 2),
	(7, 3, 'Volkswagen Golf 7', '{"category":"Citadine","fuel":"Diesel","engine":"Thermique","shift":"Manuelle","nb_portes":"5 portes"}', 250, 'disponible', 'golf.jpeg', 10),
	(8, 3, 'Peugeot 208 2018', '{"category":"Citadine","fuel":"Diesel","engine":"Thermique","shift":"Manuelle","nb_portes":"5 portes"}', 125, 'disponible', 'peugeot208.jpeg', 20);
/*!40000 ALTER TABLE `car` ENABLE KEYS */;

-- Dumping structure for table project.comment
DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `car_id` int(11) NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526CC3C6F69F` (`car_id`),
  CONSTRAINT `FK_9474526CC3C6F69F` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table project.comment: ~3 rows (approximately)
DELETE FROM `comment`;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` (`id`, `car_id`, `author`, `content`, `created_at`) VALUES
	(3, 3, 'Hugo', 'Je suis pleinement satisfait de ma location. Voiture spacieuse et confortable. Je recommande !', '2020-11-08 04:06:45'),
	(4, 8, 'Hubert', 'Super voiture ! Je l\'ai eu pendant 2 jours et c\'était parfait : confortable et spacieuse.', '2020-11-08 04:40:22'),
	(5, 4, 'Hubert', 'Superbe voiture !', '2020-11-08 23:48:06');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;

-- Dumping structure for table project.user
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table project.user: ~8 rows (approximately)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `name`, `email`, `password`, `role`) VALUES
	(1, 'Mickael', 'mickael.jackson@gmail.com', '$2y$13$iPxpQ6ApKb9Ucs78tZGU1OWXfdRIsuC5w2nQfctYucoTtyL8sOKM.', 'ROLE_RENTER'),
	(2, 'UPS', 'ups24@gmail.com', '$2y$13$EZrbaezWe6i/OGQs8L9qOO5g/rNrOd2ttZyPYo2PrYZUbvHeJL9RW', 'ROLE_CLIENT'),
	(3, 'Daniel', 'daniel.aguiar@outlook.fr', '$2y$13$.YC3uvMRTp0FCdc3nKeesexhYSPkCRcHUybQZFC/Q03r9SiP6h3sy', 'ROLE_RENTER'),
	(4, 'David', 'benibri.david@gmail.com', '$2y$13$1RxweOK7IyZH8Rk32KOgJeA4zFkXMwQdlC9x7JPHAxrA6mzsjh.yW', 'ROLE_CLIENT'),
	(5, 'Benibri', 'benibri.david1@gmail.com', '$2y$13$VUlitBPDHMEX4LLPGALZkeQkrS3z03qwEPPWf0LiSvLhPYQQEN5aO', 'ROLE_CLIENT'),
	(6, 'Hugo', 'hugo.miko@gmail.com', '$2y$13$1a44YT92WkgfsLhHdgeLduLK5MxfEa7FJn06BkRH2Ch8kRb3y1aY6', 'ROLE_CLIENT'),
	(7, 'Hubert', 'hubert@gmail.com', '$2y$13$9hsK.Vuzs9zHLlck5PY9ZObeh5uYwuSuSWWTHV4IXiARakRdJDNiG', 'ROLE_CLIENT'),
	(8, 'Sixt', 'sixt@sixt.com', '$2y$13$C2oW1hNw1gjvCATAjlfit.w.YV86t4RmmZecWsG6CXoIwRT48fp/G', 'ROLE_RENTER');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
