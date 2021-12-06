-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.13-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.3.0.6370
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table chat.mst_chat
CREATE TABLE IF NOT EXISTS `mst_chat` (
  `chat_id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_sender_id` int(11) DEFAULT NULL COMMENT 'mst_user.user_id',
  `chat_receive_id` int(11) DEFAULT NULL COMMENT 'mst_user.user_id',
  `chat_content` text COLLATE utf8mb4_bin DEFAULT NULL,
  `chat_read` tinyint(2) DEFAULT 0 COMMENT '1 = read , 0 = no read',
  `chat_type` mediumtext CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'text | file ',
  `chat_order` int(11) DEFAULT 1,
  `chat_created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`chat_id`) USING BTREE,
  KEY `chat_sender_id` (`chat_sender_id`),
  KEY `chat_receive_id` (`chat_receive_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table chat.mst_chat: ~0 rows (approximately)

-- Dumping structure for table chat.mst_typing
CREATE TABLE IF NOT EXISTS `mst_typing` (
  `typing_id` int(11) NOT NULL AUTO_INCREMENT,
  `typing_sender_id` int(11) DEFAULT NULL,
  `typing_receive_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`typing_id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table chat.mst_typing: ~0 rows (approximately)

-- Dumping structure for table chat.mst_user
CREATE TABLE IF NOT EXISTS `mst_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_full_name` varchar(50) COLLATE armscii8_bin NOT NULL,
  `user_name` varchar(50) COLLATE armscii8_bin NOT NULL,
  `user_password` varchar(255) COLLATE armscii8_bin NOT NULL,
  `user_login_status` tinyint(4) NOT NULL DEFAULT 0,
  `user_last_login_date` datetime DEFAULT NULL,
  `user_status_online` varchar(2) COLLATE armscii8_bin DEFAULT '02' COMMENT 'Online | Offline | Outside | Busy ',
  `user_ip_address` varchar(20) COLLATE armscii8_bin DEFAULT NULL,
  `user_photo` varchar(150) COLLATE armscii8_bin DEFAULT 'default.jpg',
  `user_active` tinyint(5) NOT NULL DEFAULT 1,
  `user_order` tinyint(5) NOT NULL DEFAULT 1,
  `user_created_at` datetime DEFAULT NULL,
  `user_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  KEY `user_status_online` (`user_status_online`)
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table chat.mst_user: ~0 rows (approximately)

-- Dumping structure for table chat.ref_status_online
CREATE TABLE IF NOT EXISTS `ref_status_online` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status_code` varchar(2) COLLATE armscii8_bin DEFAULT NULL,
  `status_name` varchar(50) COLLATE armscii8_bin DEFAULT NULL,
  `status_order` tinyint(2) DEFAULT 1,
  `status_active` tinyint(2) DEFAULT 1,
  `status_create_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status_code_unique` (`status_code`),
  KEY `status_code` (`status_code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table chat.ref_status_online: ~4 rows (approximately)
INSERT INTO `ref_status_online` (`id`, `status_code`, `status_name`, `status_order`, `status_active`, `status_create_at`) VALUES
	(1, '01', 'Online', 1, 1, '2021-10-05 14:07:20'),
	(2, '02', 'Offline', 2, 1, '2021-10-05 14:07:31'),
	(3, '03', 'Outside', 3, 1, '2021-10-05 14:07:44'),
	(4, '04', 'Busy', 4, 1, '2021-10-05 14:07:56');

-- Dumping structure for table chat.user_activity_log
CREATE TABLE IF NOT EXISTS `user_activity_log` (
  `user_activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_activity_module` varchar(50) COLLATE armscii8_bin DEFAULT NULL,
  `user_activity_name` varchar(30) COLLATE armscii8_bin DEFAULT NULL COMMENT 'LOGIN | LOGOUT | OTHERS',
  `user_activity_desc` varchar(50) COLLATE armscii8_bin DEFAULT NULL COMMENT 'DESCRIPTION ACTION',
  `user_activity_address` varchar(50) COLLATE armscii8_bin DEFAULT NULL COMMENT 'IP ADDRESS',
  `user_activity_browser` varchar(50) COLLATE armscii8_bin DEFAULT NULL COMMENT 'BROWSER',
  `user_activity_os` varchar(50) COLLATE armscii8_bin DEFAULT NULL,
  `user_activity_date` datetime DEFAULT NULL COMMENT 'DATE ACTIVITY USER',
  `user_id` int(11) DEFAULT NULL COMMENT 'mst_user.user_id',
  PRIMARY KEY (`user_activity_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table chat.user_activity_log: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
