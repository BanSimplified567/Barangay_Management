-- Barangay Management System Backup
-- Generated: 2025-12-27 22:57:41
-- Database: db_barangaymanagement

CREATE TABLE `tbl_announcements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `posted_by` int NOT NULL,
  `post_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `posted_by` (`posted_by`),
  CONSTRAINT `tbl_announcements_ibfk_1` FOREIGN KEY (`posted_by`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tbl_announcements` (`id`, `title`, `content`, `posted_by`, `post_date`, `created_at`) VALUES 
  ('6', 'Party People', 'Ambot sa kanding nga naay bangs', '7', '2025-12-27', '2025-12-27 21:09:30');

CREATE TABLE `tbl_blotters` (
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tbl_blotters` (`id`, `complainant_id`, `respondent_id`, `description`, `incident_date`, `status`, `resolution`, `created_at`) VALUES 
  ('1', '1', '6', 'Boundary dispute regarding property line between houses. Complainant claims respondent built fence beyond property line.', '2024-01-05', 'settled', 'Mediated settlement: Both parties agreed to have land surveyed. Respondent agreed to adjust fence if survey shows encroachment.', '2024-01-05 22:30:00'),
  ('2', '4', '7', 'Noise complaint. Complainant reports loud karaoke until 2:00 AM from respondent\'s house.', '2024-01-06', 'open', NULL, '2024-01-06 18:15:00'),
  ('3', '3', NULL, 'Lost livestock. Complainant reports missing 2 goats from his farm.', '2024-01-07', 'open', 'Case referred to barangay tanods for investigation.', '2024-01-08 00:20:00'),
  ('4', '8', '9', 'Alleged harassment. Complainant reports being followed by respondent multiple times.', '2024-01-08', 'escalated', 'Case escalated to PNP for further investigation.', '2024-01-08 19:45:00'),
  ('5', '10', NULL, 'Stray dogs in the neighborhood causing disturbance and safety concerns.', '2024-01-09', 'open', NULL, '2024-01-09 17:30:00');

CREATE TABLE `tbl_certifications` (
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tbl_certifications` (`id`, `resident_id`, `type`, `purpose`, `issue_date`, `status`, `issued_by`, `created_at`) VALUES 
  ('1', '1', 'clearance', 'Business Permit Application', '2024-01-03', 'issued', NULL, '2024-01-02 17:00:00'),
  ('2', '4', 'indigency', 'Educational Assistance', '2024-01-04', 'issued', NULL, '2024-01-03 18:30:00'),
  ('3', '6', 'residency', 'Job Application', '2025-12-27', 'issued', '7', '2024-01-04 22:15:00'),
  ('4', '8', 'clearance', 'Travel Requirements', '2024-01-06', 'rejected', NULL, '2024-01-05 19:20:00'),
  ('5', '10', 'indigency', 'Medical Assistance', '2024-01-07', 'pending', NULL, '2024-01-07 00:45:00');

CREATE TABLE `tbl_crime_records` (
  `id` int NOT NULL AUTO_INCREMENT,
  `blotter_id` int DEFAULT NULL,
  `crime_type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `incident_date` date NOT NULL,
  `location` text,
  `status` enum('reported','under_investigation','resolved') DEFAULT 'reported',
  `resolution_notes` text,
  `reported_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `blotter_id` (`blotter_id`),
  KEY `reported_by` (`reported_by`),
  CONSTRAINT `tbl_crime_records_ibfk_1` FOREIGN KEY (`blotter_id`) REFERENCES `tbl_blotters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tbl_crime_records_ibfk_2` FOREIGN KEY (`reported_by`) REFERENCES `tbl_residents` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tbl_crime_records` (`id`, `blotter_id`, `crime_type`, `description`, `incident_date`, `location`, `status`, `resolution_notes`, `reported_by`, `created_at`) VALUES 
  ('1', '1', 'Property Dispute', 'Boundary encroachment and illegal fencing', '2024-01-05', 'Purok 1, Lot 123', 'resolved', NULL, '1', '2024-01-05 23:00:00'),
  ('2', '2', 'Public Disturbance', 'Excessive noise violation during quiet hours', '2024-01-06', 'Purok 7, House #45', 'reported', NULL, '4', '2024-01-06 18:30:00'),
  ('3', '3', 'Theft', 'Livestock theft - 2 goats missing', '2024-01-07', 'Purok 3 Farm Area', 'under_investigation', NULL, '3', '2024-01-08 00:45:00'),
  ('4', '4', 'Harassment', 'Stalking and intimidation', '2024-01-08', 'Purok 8 to Purok 9 Road', 'resolved', NULL, '8', '2024-01-08 20:00:00'),
  ('5', '5', 'Vandalism', 'Graffiti on barangay hall walls', '2024-01-09', 'Barangay Hall Exterior', 'under_investigation', NULL, '5', '2024-01-09 22:15:00');

CREATE TABLE `tbl_events` (
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tbl_events` (`id`, `name`, `description`, `event_date`, `location`, `organizer_id`, `status`, `created_at`) VALUES 
  ('1', 'Barangay Fiesta', 'Annual barangay fiesta celebration with games, food, and entertainment', '2024-01-20', 'Barangay Covered Court', NULL, 'upcoming', '2023-12-20 16:00:00'),
  ('2', 'Medical Mission', 'Free medical check-up and medicines for residents', '2024-01-15', 'Barangay Health Center', NULL, 'completed', '2023-12-25 18:30:00'),
  ('3', 'Sports Festival', 'Inter-purok basketball and volleyball tournament', '2023-12-28', 'Barangay Basketball Court', NULL, 'completed', '2023-12-10 22:15:00'),
  ('4', 'Senior Citizens Day', 'Special program for senior citizens with free meals and gifts', '2024-01-25', 'Barangay Hall', NULL, 'upcoming', '2024-01-02 17:20:00'),
  ('5', 'Youth Leadership Training', 'Leadership workshop for barangay youth', '2024-02-10', 'Barangay Session Hall', NULL, 'ongoing', '2024-01-04 00:45:00');

CREATE TABLE `tbl_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `action` text NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tbl_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tbl_logs` (`id`, `user_id`, `action`, `timestamp`) VALUES 
  ('26', '7', 'New user registration: Jade Ivan banban Bringcola', '2025-12-27 18:28:04'),
  ('27', '7', 'User logged in', '2025-12-27 18:28:13'),
  ('28', '7', 'User logged in', '2025-12-27 18:32:47'),
  ('29', '7', 'Marked crime as under investigation: Vandalism (ID: 5)', '2025-12-27 19:51:19'),
  ('30', '7', 'User logged in', '2025-12-27 20:42:06'),
  ('31', '7', 'Updated resident: Sofia Tan (ID: 6)', '2025-12-27 21:00:50'),
  ('32', '7', 'Posted announcement: Party People (ID: 6)', '2025-12-27 21:09:30'),
  ('33', '7', 'Updated crime record: Harassment (ID: 4)', '2025-12-27 21:17:56'),
  ('34', '7', 'Approved residency certification for Sofia Tan', '2025-12-27 21:19:02'),
  ('35', '7', 'Updated event: Medical Mission (ID: 2)', '2025-12-27 21:19:37'),
  ('36', '7', 'Updated event: Youth Leadership Training (ID: 5)', '2025-12-27 21:19:49'),
  ('37', '7', 'Updated project: Multi-purpose Hall Construction (ID: 5)', '2025-12-27 21:23:48'),
  ('38', '7', 'Created project: Tulay Ni labay (ID: 6)', '2025-12-27 21:24:49'),
  ('39', '7', 'Marked project as completed: Tulay Ni labay (ID: 6)', '2025-12-27 21:24:54'),
  ('40', '9', 'New user registration: Jade Ivan banban Bringcola', '2025-12-28 06:32:20'),
  ('41', '10', 'New user registration: Jade Ivan Bringcola', '2025-12-28 06:33:04'),
  ('42', '9', 'User logged in', '2025-12-28 06:33:46'),
  ('43', '9', 'User logged in', '2025-12-28 06:40:42'),
  ('44', '7', 'User logged in', '2025-12-28 06:41:16'),
  ('45', '7', 'Updated system settings', '2025-12-28 06:57:29');

CREATE TABLE `tbl_officials` (
  `id` int NOT NULL AUTO_INCREMENT,
  `resident_id` int NOT NULL,
  `position` varchar(255) NOT NULL,
  `term_start` date DEFAULT NULL,
  `term_end` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `resident_id` (`resident_id`),
  CONSTRAINT `tbl_officials_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `tbl_residents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tbl_officials` (`id`, `resident_id`, `position`, `term_start`, `term_end`, `created_at`) VALUES 
  ('1', '1', 'Barangay Captain', '2023-07-01', '2025-06-30', '2024-01-01 17:00:00'),
  ('2', '2', 'Barangay Secretary', '2023-07-01', '2025-06-30', '2024-01-01 17:30:00'),
  ('3', '3', 'Barangay Treasurer', '2023-07-01', '2025-06-30', '2024-01-01 18:00:00'),
  ('4', '4', 'Barangay Councilor', '2023-07-01', '2025-06-30', '2024-01-01 18:30:00'),
  ('5', '5', 'Barangay Councilor', '2023-07-01', '2025-06-30', '2024-01-01 19:00:00');

CREATE TABLE `tbl_projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `budget` decimal(15,2) DEFAULT '0.00',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('planning','ongoing','completed','cancelled') DEFAULT 'planning',
  `location` varchar(500) DEFAULT NULL,
  `project_lead` varchar(255) DEFAULT NULL,
  `funding_source` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tbl_projects` (`id`, `name`, `description`, `budget`, `start_date`, `end_date`, `status`, `location`, `project_lead`, `funding_source`, `created_at`, `updated_at`) VALUES 
  ('1', 'Barangay Health Center Renovation', 'Renovation and upgrading of the barangay health center with new medical equipment', '500000.00', '2024-02-01', '2024-05-31', 'planning', 'Barangay Hall Compound', 'Dr. Maria Santos', 'DOH & Barangay Funds', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('2', 'Road Concreting Project', 'Concreting of 500-meter barangay road in Purok 3', '750000.00', '2024-01-15', '2024-04-15', 'ongoing', 'Purok 3, Sitio Maligaya', 'Engr. Juan Dela Cruz', 'DPWH & Barangay Funds', '2024-01-02 17:30:00', '2025-12-26 11:48:40'),
  ('3', 'Solar Street Lights Installation', 'Installation of 50 solar-powered street lights in major barangay roads', '300000.00', '2023-11-01', '2023-12-31', 'completed', 'Main Barangay Roads', 'Kag. Pedro Reyes', 'DILG & Barangay Funds', '2023-10-15 18:15:00', '2025-12-26 11:48:40'),
  ('4', 'Drainage System Improvement', 'Improvement of drainage system in flood-prone areas', '400000.00', '2024-03-01', '2024-06-30', 'planning', 'Purok 5 and 6', 'Engr. Roberto Garcia', 'Barangay Funds', '2024-01-03 22:20:00', '2025-12-26 11:48:40'),
  ('5', 'Multi-purpose Hall Construction', 'Construction of barangay multi-purpose hall for events and meetings', '1200000.00', '2024-07-01', '2024-12-31', 'planning', 'Barangay Open Space', 'Arch. Sofia Tan', 'DILG & Congressional Funds', '2024-01-05 00:45:00', '2025-12-26 11:48:40'),
  ('6', 'Tulay Ni labay', 'Syempre u know the drill contracture ngani', '10000000.00', '2025-12-27', '2025-12-27', 'completed', 'Cebu', 'Bannie Ngani', 'DOH', '2025-12-27 21:24:49', '2025-12-27 21:24:54');

CREATE TABLE `tbl_residents` (
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
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tbl_residents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tbl_residents` (`id`, `user_id`, `full_name`, `address`, `birthdate`, `gender`, `contact_number`, `occupation`, `civil_status`, `created_at`, `updated_at`) VALUES 
  ('1', NULL, 'Juan Dela Cruz', '123 Purok 1, Barangay Sibonga', '1985-03-15', 'male', '09171234567', 'Farmer', 'married', '2024-01-02 17:00:00', '2025-12-26 11:48:40'),
  ('2', NULL, 'Maria Santos', '456 Purok 2, Barangay Sibonga', '1990-07-22', 'female', '09172345678', 'Teacher', 'married', '2024-01-02 18:00:00', '2025-12-26 11:48:40'),
  ('3', NULL, 'Pedro Reyes', '789 Purok 3, Barangay Sibonga', '1978-11-30', 'male', '09173456789', 'Fisherman', 'widowed', '2024-01-03 19:00:00', '2025-12-26 11:48:40'),
  ('4', NULL, 'Ana Lopez', '321 Purok 4, Barangay Sibonga', '1995-05-18', 'female', '09174567890', 'Nurse', 'single', '2024-01-04 22:00:00', '2025-12-26 11:48:40'),
  ('5', NULL, 'Roberto Garcia', '654 Purok 5, Barangay Sibonga', '1982-09-25', 'male', '09175678901', 'Driver', 'married', '2024-01-05 23:00:00', '2025-12-26 11:48:40'),
  ('6', NULL, 'Sofia Tan', '987 Purok 6, Barangay Sibonga', '1992-12-10', 'female', '09176789012', 'Business Owner', 'single', '2024-01-07 00:00:00', '2025-12-26 11:48:40'),
  ('7', NULL, 'Miguel Torres', '147 Purok 7, Barangay Sibonga', '1975-04-05', 'male', '09177890123', 'Carpenter', 'divorced', '2024-01-08 01:00:00', '2025-12-26 11:48:40'),
  ('8', NULL, 'Carmen Rivera', '258 Purok 8, Barangay Sibonga', '1988-08-12', 'female', '09178901234', 'Housewife', 'married', '2024-01-09 02:00:00', '2025-12-26 11:48:40'),
  ('9', NULL, 'Antonio Cruz', '369 Purok 9, Barangay Sibonga', '1998-02-28', 'male', '09179012345', 'Student', 'single', '2024-01-10 03:00:00', '2025-12-26 11:48:40'),
  ('10', NULL, 'Elena Mendoza', '159 Purok 10, Barangay Sibonga', '1993-06-14', 'female', '09170123456', 'Accountant', 'single', '2024-01-11 04:00:00', '2025-12-26 11:48:40'),
  ('11', NULL, 'Jade Ivan banban Bringcola', '', '2025-12-26', 'other', NULL, NULL, 'single', '2025-12-26 11:50:39', '2025-12-26 11:50:39'),
  ('12', '7', 'Jade Ivan banban Bringcola', '', '2025-12-27', 'other', NULL, NULL, 'single', '2025-12-27 18:28:03', '2025-12-27 18:28:03'),
  ('13', '9', 'Jade Ivan banban Bringcola', '', '2025-12-28', 'other', NULL, NULL, 'single', '2025-12-28 06:32:20', '2025-12-28 06:32:20'),
  ('14', '10', 'Jade Ivan Bringcola', '', '2025-12-28', 'other', NULL, NULL, 'single', '2025-12-28 06:33:04', '2025-12-28 06:33:04');

CREATE TABLE `tbl_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text,
  `description` text,
  `setting_group` varchar(50) DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tbl_settings` (`id`, `setting_key`, `setting_value`, `description`, `setting_group`, `created_at`, `updated_at`) VALUES 
  ('1', 'system_name', 'Barangay Management System', 'Display name of the system', 'general', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('2', 'system_version', '1.0.0', 'Current system version', 'general', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('3', 'system_email', 'admin@barangay.ph', 'Default system email address', 'general', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('4', 'default_items_per_page', '25', 'Default number of items per page in tables', 'general', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('5', 'session_timeout', '30', 'Session timeout in minutes', 'security', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('6', 'maintenance_mode', '0', 'Enable/disable maintenance mode', 'system', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('7', 'email_notifications', '1', 'Enable/disable email notifications', 'email', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('8', 'email_smtp_host', 'localhost', 'SMTP server host', 'email', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('9', 'email_smtp_port', '25', 'SMTP server port', 'email', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('10', 'backup_auto', '0', 'Enable/disable automatic backups', 'backup', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('11', 'backup_interval', '7', 'Days between automatic backups', 'backup', '2024-01-01 16:00:00', '2025-12-28 06:57:29'),
  ('12', 'login_attempts', '5', 'Maximum failed login attempts before lockout', 'security', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('13', 'password_expiry', '90', 'Days before password expires', 'security', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('14', 'registration_allowed', '1', 'Allow/disable new user registration', 'system', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('15', 'theme', 'default', 'System theme', 'general', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('16', 'date_format', 'Y-m-d', 'Default date format', 'general', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('17', 'time_format', 'H:i:s', 'Default time format', 'general', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('18', 'currency_symbol', '₱', 'Currency symbol', 'general', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('19', 'enable_audit_log', '1', 'Enable/disable audit logging', 'system', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('20', 'enable_activity_log', '1', 'Enable/disable activity logging', 'system', '2024-01-01 16:00:00', '2025-12-26 11:48:40'),
  ('21', 'backup_keep', '5', NULL, 'general', '2025-12-28 06:57:29', '2025-12-28 06:57:29'),
  ('22', 'backup_time', '00:00', NULL, 'general', '2025-12-28 06:57:29', '2025-12-28 06:57:29'),
  ('23', 'backup_compress', '0', NULL, 'general', '2025-12-28 06:57:29', '2025-12-28 06:57:29');

CREATE TABLE `tbl_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('resident','staff','admin') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tbl_users` (`id`, `full_name`, `email`, `password`, `role`, `created_at`) VALUES 
  ('7', 'Jade Ivan banban Bringcola', 'ivanjade567@gmail.com', '$2y$12$HvzuaisP1SwAPMV/08PUWOUNJyFJ18aI9BYKegB8vce8TL.ZTNR/a', 'admin', '2025-12-27 18:28:03'),
  ('9', 'Jade Ivan banban Bringcola', 'naviedaj567@gmail.com', '$2y$12$mEpvqtSvE3bpNf0y8.c6hO2nquqHK25nqnu8EfBgNqugEbH7G/4ra', 'staff', '2025-12-28 06:32:20'),
  ('10', 'Jade Ivan Bringcola', 'bansimplified567@gmail.com', '$2y$12$AgD9g26vuTnadRD0WM22feKmpQxkZqgmPV6a4vHXQ3WRxSVUP55dm', 'resident', '2025-12-28 06:33:04');

