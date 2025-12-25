-- --------------------------------------------------------
-- Host:                         localhost
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


-- Dumping database structure for db_barangaymanagement
CREATE DATABASE IF NOT EXISTS `db_barangaymanagement` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_barangaymanagement`;

-- Dumping structure for table db_barangaymanagement.tbl_announcements
CREATE TABLE IF NOT EXISTS `tbl_announcements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `posted_by` int NOT NULL,
  `post_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `posted_by` (`posted_by`),
  CONSTRAINT `tbl_announcements_ibfk_1` FOREIGN KEY (`posted_by`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_barangaymanagement.tbl_announcements: ~0 rows (approximately)

-- Dumping structure for table db_barangaymanagement.tbl_blotters
CREATE TABLE IF NOT EXISTS `tbl_blotters` (
  `id` int NOT NULL AUTO_INCREMENT,
  `complainant_id` int NOT NULL,
  `respondent_id` int DEFAULT NULL,
  `description` text NOT NULL,
  `incident_date` date NOT NULL,
  `status` enum('open','settled','escalated') DEFAULT 'open',
  `resolution` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `complainant_id` (`complainant_id`),
  KEY `respondent_id` (`respondent_id`),
  CONSTRAINT `tbl_blotters_ibfk_1` FOREIGN KEY (`complainant_id`) REFERENCES `tbl_residents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tbl_blotters_ibfk_2` FOREIGN KEY (`respondent_id`) REFERENCES `tbl_residents` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_barangaymanagement.tbl_blotters: ~0 rows (approximately)

-- Dumping structure for table db_barangaymanagement.tbl_certifications
CREATE TABLE IF NOT EXISTS `tbl_certifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `resident_id` int NOT NULL,
  `type` enum('clearance','indigency','residency','other') NOT NULL,
  `purpose` text,
  `issue_date` date NOT NULL,
  `status` enum('pending','issued','rejected') DEFAULT 'pending',
  `issued_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `resident_id` (`resident_id`),
  KEY `issued_by` (`issued_by`),
  CONSTRAINT `tbl_certifications_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `tbl_residents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tbl_certifications_ibfk_2` FOREIGN KEY (`issued_by`) REFERENCES `tbl_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_barangaymanagement.tbl_certifications: ~0 rows (approximately)

-- Dumping structure for table db_barangaymanagement.tbl_crime_records
CREATE TABLE IF NOT EXISTS `tbl_crime_records` (
  `id` int NOT NULL AUTO_INCREMENT,
  `blotter_id` int DEFAULT NULL,
  `crime_type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `incident_date` date NOT NULL,
  `location` text,
  `status` enum('reported','under_investigation','resolved') DEFAULT 'reported',
  `reported_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `blotter_id` (`blotter_id`),
  KEY `reported_by` (`reported_by`),
  CONSTRAINT `tbl_crime_records_ibfk_1` FOREIGN KEY (`blotter_id`) REFERENCES `tbl_blotters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tbl_crime_records_ibfk_2` FOREIGN KEY (`reported_by`) REFERENCES `tbl_residents` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_barangaymanagement.tbl_crime_records: ~0 rows (approximately)

-- Dumping structure for table db_barangaymanagement.tbl_events
CREATE TABLE IF NOT EXISTS `tbl_events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `event_date` date NOT NULL,
  `location` text,
  `organizer_id` int DEFAULT NULL,
  `status` enum('upcoming','ongoing','completed','cancelled') DEFAULT 'upcoming',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `organizer_id` (`organizer_id`),
  CONSTRAINT `tbl_events_ibfk_1` FOREIGN KEY (`organizer_id`) REFERENCES `tbl_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_barangaymanagement.tbl_events: ~0 rows (approximately)

-- Dumping structure for table db_barangaymanagement.tbl_logs
CREATE TABLE IF NOT EXISTS `tbl_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `action` text NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tbl_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_barangaymanagement.tbl_logs: ~4 rows (approximately)
INSERT INTO `tbl_logs` (`id`, `user_id`, `action`, `timestamp`) VALUES
	(1, 1, 'Added resident: Jade Ivan banban Bringcola', '2025-12-24 10:27:04'),
	(2, 1, 'Added resident: Jade Ivan banban Bringcola', '2025-12-24 10:27:04'),
	(3, 1, 'Added resident: Jade Ivan banban Bringcola', '2025-12-24 10:27:04'),
	(4, 1, 'Added resident: Banni', '2025-12-24 10:27:43');

-- Dumping structure for table db_barangaymanagement.tbl_officials
CREATE TABLE IF NOT EXISTS `tbl_officials` (
  `id` int NOT NULL AUTO_INCREMENT,
  `resident_id` int NOT NULL,
  `position` varchar(255) NOT NULL,
  `term_start` date DEFAULT NULL,
  `term_end` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `resident_id` (`resident_id`),
  CONSTRAINT `tbl_officials_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `tbl_residents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_barangaymanagement.tbl_officials: ~0 rows (approximately)

-- Dumping structure for table db_barangaymanagement.tbl_residents
CREATE TABLE IF NOT EXISTS `tbl_residents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `birthdate` date NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `civil_status` enum('single','married','widowed','divorced') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tbl_residents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_barangaymanagement.tbl_residents: ~4 rows (approximately)
INSERT INTO `tbl_residents` (`id`, `user_id`, `full_name`, `address`, `birthdate`, `gender`, `contact_number`, `occupation`, `civil_status`, `created_at`) VALUES
	(1, NULL, 'Jade Ivan banban Bringcola', 'SM', '2022-09-20', 'male', '987654321', 'ok', 'single', '2025-12-24 10:25:04'),
	(2, NULL, 'Jade Ivan banban Bringcola', 'SM', '2022-09-20', 'male', '987654321', 'ok', 'single', '2025-12-24 10:27:04'),
	(3, NULL, 'Jade Ivan banban Bringcola', 'SM', '2022-09-20', 'male', '987654321', 'ok', 'single', '2025-12-24 10:27:04'),
	(4, NULL, 'Jade Ivan banban Bringcola', 'SM', '2022-09-20', 'male', '987654321', 'ok', 'single', '2025-12-24 10:27:04');

-- Dumping structure for table db_barangaymanagement.tbl_users
CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('resident','staff','admin') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_barangaymanagement.tbl_users: ~0 rows (approximately)
INSERT INTO `tbl_users` (`id`, `full_name`, `email`, `password`, `role`, `created_at`) VALUES
	(1, 'bannie', 'naviedaj567@gmail.com', '$2y$12$x4K49m1uLeyZFFyQIi8R.eAeQxag0oN2GTPtLggf.efsmog6Il4FO', 'admin', '2025-12-24 06:24:31');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
