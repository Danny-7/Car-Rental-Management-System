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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table project.billing: ~11 rows (approximately)
DELETE FROM `billing`;
/*!40000 ALTER TABLE `billing` DISABLE KEYS */;
INSERT INTO `billing` (`id`, `id_user_id`, `id_car_id`, `start_date`, `end_date`, `price`, `paid`, `returned`) VALUES
	(8, 6, 2, '2020-11-18', '2020-11-28', 120, 1, 1),
	(9, 6, 2, '2020-11-18', '2020-11-28', 120, 1, 0),
	(10, 6, 2, '2020-11-18', '2020-11-28', 120, 1, 0),
	(11, 6, 2, '2020-11-18', '2020-11-28', 120, 1, 0),
	(12, 6, 1, '2020-11-27', '2021-01-22', 300, 1, 1),
	(13, 6, 1, '2020-11-27', '2021-01-22', 300, 1, 0),
	(14, 6, 1, '2020-11-27', '2021-01-22', 300, 1, 0),
	(15, 6, 1, '2020-11-27', '2021-01-22', 300, 1, 0),
	(16, 6, 1, '2020-11-27', '2021-01-22', 300, 1, 0),
	(17, 6, 1, '2020-11-27', '2021-01-22', 300, 1, 0),
	(18, 6, 3, '2020-11-27', '2020-12-18', 270, 1, 1),
	(19, 6, 6, '2020-11-19', '2020-11-27', 630, 1, 0);
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
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_773DE69D2EE78D6C` (`id_owner_id`),
  CONSTRAINT `FK_773DE69D2EE78D6C` FOREIGN KEY (`id_owner_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table project.car: ~7 rows (approximately)
DELETE FROM `car`;
/*!40000 ALTER TABLE `car` DISABLE KEYS */;
INSERT INTO `car` (`id`, `id_owner_id`, `type`, `datasheet`, `amount`, `rent`, `image`, `quantity`) VALUES
	(1, 1, 'Mercedes Classe A', '{"category":"Citadine","fuel":"Essence","engine":"Thermique","shift":"Automatique","nb_portes":"5 portes"}', 300, 'disponible', 'mercedesA.png', 7),
	(2, 1, 'Clio 4', '{"category":"Citadine","fuel":"Diesel","engine":"Thermique","shift":"Manuelle","nb_portes":"5 portes"}', 120, 'disponible', 'S7-modele-renault-clio-4.jpeg', 8),
	(3, 1, 'Audi A3 berline', '{"category":"Berline","fuel":"Essence","engine":"Thermique","shift":"Manuelle","nb_portes":"5 portes"}', 300, 'disponible', 'audi-a3.jpeg', 15),
	(4, 3, 'Mercedes Classe C', '{"category":"Berline","fuel":"Essence","engine":"Thermique","shift":"Automatique","nb_portes":"5 portes"}', 400, 'disponible', 'mercedes-benz-c-class.jpeg', 5),
	(5, 3, 'Porsche Macan  Turbo S 2020', '{"category":"4x4, SUV, Crossover","fuel":"Essence","engine":"Thermique","shift":"Automatique","nb_portes":"5 portes"}', 600, 'disponible', 'macan.png', 9),
	(6, 3, 'Porsche Cayenne 2018', '{"category":"4x4, SUV, Crossover","fuel":"Essence","engine":"Thermique","shift":"Automatique","nb_portes":"5 portes"}', 700, 'disponible', 'cayennejpg.jpeg', 5),
	(7, 3, 'Volkswagen Golf 7', '{"category":"Citadine","fuel":"Diesel","engine":"Thermique","shift":"Manuelle","nb_portes":"5 portes"}', 250, 'disponible', 'golf.jpeg', 15),
	(8, 3, 'Peugeot 208 2018', '{"category":"Citadine","fuel":"Diesel","engine":"Thermique","shift":"Manuelle","nb_portes":"5 portes"}', 125, 'disponible', 'peugeot208.jpeg', 20);
/*!40000 ALTER TABLE `car` ENABLE KEYS */;

-- Dumping structure for table project.user
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table project.user: ~5 rows (approximately)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `name`, `email`, `password`, `role`) VALUES
	(1, 'Mickael', 'mickael.jackson@gmail.com', '$2y$13$iPxpQ6ApKb9Ucs78tZGU1OWXfdRIsuC5w2nQfctYucoTtyL8sOKM.', 'Loueur'),
	(2, 'UPS', 'ups24@gmail.com', '$2y$13$EZrbaezWe6i/OGQs8L9qOO5g/rNrOd2ttZyPYo2PrYZUbvHeJL9RW', 'Entreprise'),
	(3, 'Daniel', 'daniel.aguiar@outlook.fr', '$2y$13$.YC3uvMRTp0FCdc3nKeesexhYSPkCRcHUybQZFC/Q03r9SiP6h3sy', 'Loueur'),
	(4, 'David', 'benibri.david@gmail.com', '$2y$13$1RxweOK7IyZH8Rk32KOgJeA4zFkXMwQdlC9x7JPHAxrA6mzsjh.yW', 'Entreprise'),
	(5, 'Benibri', 'benibri.david1@gmail.com', '$2y$13$VUlitBPDHMEX4LLPGALZkeQkrS3z03qwEPPWf0LiSvLhPYQQEN5aO', 'Entreprise'),
	(6, 'Hugo', 'hugo.miko@gmail.com', '$2y$13$1a44YT92WkgfsLhHdgeLduLK5MxfEa7FJn06BkRH2Ch8kRb3y1aY6', 'Entreprise');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
