-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for siqar_db
CREATE DATABASE IF NOT EXISTS `siqar_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `siqar_db`;

-- Dumping structure for table siqar_db.building
CREATE TABLE IF NOT EXISTS `building` (
  `building_id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `latitude_longtitude` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `radius` int NOT NULL,
  PRIMARY KEY (`building_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table siqar_db.building: ~0 rows (approximately)
INSERT INTO `building` (`building_id`, `code`, `name`, `address`, `latitude_longtitude`, `radius`) VALUES
	(7, '2025-E9228', 'Kantor', 'Jl Kencana Loka', '-6.3045632,106.6991616', 900);

-- Dumping structure for table siqar_db.business_card
CREATE TABLE IF NOT EXISTS `business_card` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `photo` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `active` varchar(1) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table siqar_db.business_card: ~1 rows (approximately)
INSERT INTO `business_card` (`id`, `name`, `photo`, `active`) VALUES
	(1, 'Thema 1', '2021-09-14818db55ed84f450043ad72540c19d46e.jpg', '1');

-- Dumping structure for table siqar_db.cuty
CREATE TABLE IF NOT EXISTS `cuty` (
  `cuty_id` int NOT NULL AUTO_INCREMENT,
  `employees_id` int NOT NULL,
  `cuty_start` date NOT NULL,
  `cuty_end` date NOT NULL,
  `date_work` date NOT NULL,
  `cuty_total` int NOT NULL,
  `cuty_description` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `cuty_status` int NOT NULL,
  PRIMARY KEY (`cuty_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table siqar_db.cuty: ~0 rows (approximately)

-- Dumping structure for table siqar_db.employees
CREATE TABLE IF NOT EXISTS `employees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employees_code` varchar(35) COLLATE utf8mb4_general_ci NOT NULL,
  `employees_nip` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `employees_email` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `employees_password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `employees_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `position_id` int NOT NULL,
  `shift_id` int NOT NULL,
  `building_id` int NOT NULL,
  `photo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_login` datetime NOT NULL,
  `created_cookies` varchar(70) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table siqar_db.employees: ~0 rows (approximately)

-- Dumping structure for table siqar_db.holiday
CREATE TABLE IF NOT EXISTS `holiday` (
  `holiday_id` int NOT NULL AUTO_INCREMENT,
  `holiday_date` date NOT NULL,
  `description` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`holiday_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table siqar_db.holiday: ~0 rows (approximately)

-- Dumping structure for table siqar_db.permission
CREATE TABLE IF NOT EXISTS `permission` (
  `permission_id` int NOT NULL AUTO_INCREMENT,
  `employees_id` int NOT NULL,
  `permission_name` varchar(35) COLLATE utf8mb4_general_ci NOT NULL,
  `permission_date` date NOT NULL,
  `permission_date_finish` date NOT NULL,
  `permission_description` text COLLATE utf8mb4_general_ci NOT NULL,
  `files` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `date` date NOT NULL,
  `status` varchar(2) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table siqar_db.permission: ~0 rows (approximately)

-- Dumping structure for table siqar_db.position
CREATE TABLE IF NOT EXISTS `position` (
  `position_id` int NOT NULL AUTO_INCREMENT,
  `position_name` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`position_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table siqar_db.position: ~3 rows (approximately)
INSERT INTO `position` (`position_id`, `position_name`) VALUES
	(1, 'STAFF'),
	(2, 'ACCOUNTING'),
	(7, 'MARKETING');

-- Dumping structure for table siqar_db.presence
CREATE TABLE IF NOT EXISTS `presence` (
  `presence_id` int NOT NULL AUTO_INCREMENT,
  `employees_id` int NOT NULL,
  `presence_date` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  `present_id` int NOT NULL COMMENT 'Masuk,Pulang,Tidak Hadir',
  `latitude_longtitude_in` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `latitude_longtitude_out` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `information` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`presence_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table siqar_db.presence: ~6 rows (approximately)
INSERT INTO `presence` (`presence_id`, `employees_id`, `presence_date`, `time_in`, `time_out`, `present_id`, `latitude_longtitude_in`, `latitude_longtitude_out`, `information`) VALUES
	(28, 6, '2022-07-15', '14:54:44', '00:00:00', 1, '-5.3973627,105.2546003', '', ''),
	(29, 27, '2022-10-21', '14:29:17', '21:34:13', 1, '-5.4027714,105.2601946', '-5.3971396,105.2667887', ''),
	(30, 27, '2023-10-03', '14:34:53', '00:00:00', 1, '-5.390336,105.299968', '', ''),
	(31, 28, '2023-10-03', '14:41:38', '00:00:00', 1, '-5.3929889,105.2999419', '', ''),
	(32, 27, '2025-04-21', '11:54:03', '00:00:00', 1, '-6.324224,106.6991616', '', ''),
	(33, 27, '2025-04-23', '18:38:36', '18:38:36', 1, '-6.2813003,106.8391858', '-6.2813003,106.8391858', '');

-- Dumping structure for table siqar_db.present_status
CREATE TABLE IF NOT EXISTS `present_status` (
  `present_id` int NOT NULL AUTO_INCREMENT,
  `present_name` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`present_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table siqar_db.present_status: ~5 rows (approximately)
INSERT INTO `present_status` (`present_id`, `present_name`) VALUES
	(1, 'Hadir'),
	(2, 'Sakit'),
	(3, 'Izin'),
	(4, 'Dinas Luar Kota'),
	(5, 'Dinas Dalam Kota');

-- Dumping structure for table siqar_db.shift
CREATE TABLE IF NOT EXISTS `shift` (
  `shift_id` int NOT NULL AUTO_INCREMENT,
  `shift_name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  PRIMARY KEY (`shift_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table siqar_db.shift: ~1 rows (approximately)
INSERT INTO `shift` (`shift_id`, `shift_name`, `time_in`, `time_out`) VALUES
	(1, 'FULL TIME', '07:30:00', '17:00:00');

-- Dumping structure for table siqar_db.sw_site
CREATE TABLE IF NOT EXISTS `sw_site` (
  `site_id` int NOT NULL AUTO_INCREMENT,
  `site_url` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `site_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `site_company` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `site_manager` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `site_director` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `site_phone` char(12) COLLATE utf8mb4_general_ci NOT NULL,
  `site_address` text COLLATE utf8mb4_general_ci NOT NULL,
  `site_description` text COLLATE utf8mb4_general_ci NOT NULL,
  `site_logo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `site_email` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `site_email_domain` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `gmail_host` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `gmail_username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `gmail_password` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `gmail_port` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`site_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table siqar_db.sw_site: ~1 rows (approximately)
INSERT INTO `sw_site` (`site_id`, `site_url`, `site_name`, `site_company`, `site_manager`, `site_director`, `site_phone`, `site_address`, `site_description`, `site_logo`, `site_email`, `site_email_domain`, `gmail_host`, `gmail_username`, `gmail_password`, `gmail_port`) VALUES
	(1, 'http://localhost/siqar', 'SIQAR', 'Prass', 'Wir', 'Tams', '0123456789', 'Jakarta', 'Sistem QR Absensi Radius', 'logo-tsjpg.jpg', 'jawnich@kiw.co', 'info@domain.com', 'smtp.gmail.com', 'emailanda@gmail.com', 'passwordemailserver', '465');

-- Dumping structure for table siqar_db.user
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `fullname` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `registered` datetime NOT NULL,
  `created_login` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `session` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `ip` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `browser` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `level` int NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table siqar_db.user: ~1 rows (approximately)
INSERT INTO `user` (`user_id`, `username`, `email`, `password`, `fullname`, `registered`, `created_login`, `last_login`, `session`, `ip`, `browser`, `level`) VALUES
	(3, 'siqar', 'admin@siqar.com', '88222999e01f1910a5ac39fa37d3b8b704973d503d0ff7c84d46305b92cfa0f6', 'Siqar Admin', '2025-01-13 19:48:07', '2025-04-24 12:00:59', '2025-04-24 11:44:39', '-', '1', 'Google Crome', 1);

-- Dumping structure for table siqar_db.user_level
CREATE TABLE IF NOT EXISTS `user_level` (
  `level_id` int NOT NULL AUTO_INCREMENT,
  `level_name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table siqar_db.user_level: ~2 rows (approximately)
INSERT INTO `user_level` (`level_id`, `level_name`) VALUES
	(1, 'Administrator'),
	(2, 'Operator');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
