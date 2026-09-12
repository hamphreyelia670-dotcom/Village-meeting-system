-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: village_management_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `details` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`activity_id`),
  KEY `created_at` (`created_at`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agendas`
--

DROP TABLE IF EXISTS `agendas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agendas` (
  `AgendaId` int(11) NOT NULL AUTO_INCREMENT,
  `MeetingId` int(11) NOT NULL,
  `Title` varchar(200) NOT NULL,
  `Description` text DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`AgendaId`),
  KEY `fk_agenda_meeting` (`MeetingId`),
  CONSTRAINT `fk_agenda_meeting` FOREIGN KEY (`MeetingId`) REFERENCES `meetings` (`Meeting_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `announcement`
--

DROP TABLE IF EXISTS `announcement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcement` (
  `AnnouncementId` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(200) NOT NULL,
  `Message` text NOT NULL,
  `Published` tinyint(1) DEFAULT 0,
  `Createdby` int(11) NOT NULL,
  `Createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`AnnouncementId`),
  KEY `fk_announcement_user` (`Createdby`),
  CONSTRAINT `fk_announcement_user` FOREIGN KEY (`Createdby`) REFERENCES `users` (`userId`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendance` (
  `AttendanceID` int(11) NOT NULL AUTO_INCREMENT,
  `MeetingId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `Attendancestatus` enum('present','absent') NOT NULL,
  `Date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`AttendanceID`),
  UNIQUE KEY `unique_attendance` (`MeetingId`,`UserId`),
  KEY `fk_attendance_user` (`UserId`),
  CONSTRAINT `fk_attendance_meeting` FOREIGN KEY (`MeetingId`) REFERENCES `meetings` (`Meeting_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_attendance_user` FOREIGN KEY (`UserId`) REFERENCES `users` (`userId`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bulk_sms`
--

DROP TABLE IF EXISTS `bulk_sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bulk_sms` (
  `Sms_id` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(200) DEFAULT NULL,
  `Message` text NOT NULL,
  `MeetingId` int(11) DEFAULT NULL,
  `AnnouncementId` int(11) DEFAULT NULL,
  `Createdby` int(11) NOT NULL,
  `Createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`Sms_id`),
  KEY `fk_sms_meeting` (`MeetingId`),
  KEY `fk_sms_announcement` (`AnnouncementId`),
  KEY `fk_sms_user` (`Createdby`),
  CONSTRAINT `fk_sms_announcement` FOREIGN KEY (`AnnouncementId`) REFERENCES `announcement` (`AnnouncementId`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_sms_meeting` FOREIGN KEY (`MeetingId`) REFERENCES `meetings` (`Meeting_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_sms_user` FOREIGN KEY (`Createdby`) REFERENCES `users` (`userId`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback` (
  `FeedbackId` int(11) NOT NULL AUTO_INCREMENT,
  `UserId` int(11) NOT NULL,
  `Subject` varchar(200) NOT NULL,
  `Message` text NOT NULL,
  `Status` enum('pending','reviewed','resolved') DEFAULT 'pending',
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`FeedbackId`),
  KEY `fk_feedback_user` (`UserId`),
  CONSTRAINT `fk_feedback_user` FOREIGN KEY (`UserId`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `meetings`
--

DROP TABLE IF EXISTS `meetings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meetings` (
  `Meeting_id` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(150) NOT NULL,
  `Description` text DEFAULT NULL,
  `Meetingdate` date NOT NULL,
  `Meetingtime` time NOT NULL,
  `Location` varchar(150) NOT NULL,
  `Status` enum('upcoming','ongoing','completed','cancelled') DEFAULT 'upcoming',
  `CreatedBy` int(11) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`Meeting_id`),
  KEY `fk_meeting_user` (`CreatedBy`),
  CONSTRAINT `fk_meeting_user` FOREIGN KEY (`CreatedBy`) REFERENCES `users` (`userId`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `minutes`
--

DROP TABLE IF EXISTS `minutes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `minutes` (
  `MinutesId` int(11) NOT NULL AUTO_INCREMENT,
  `MeetingId` int(11) NOT NULL,
  `Content` longtext DEFAULT NULL,
  `Filepath` varchar(255) DEFAULT NULL,
  `Published` tinyint(1) DEFAULT 0,
  `Createdby` int(11) NOT NULL,
  `Createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`MinutesId`),
  UNIQUE KEY `MeetingId` (`MeetingId`),
  KEY `fk_minutes_user` (`Createdby`),
  CONSTRAINT `fk_minutes_meeting` FOREIGN KEY (`MeetingId`) REFERENCES `meetings` (`Meeting_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_minutes_user` FOREIGN KEY (`Createdby`) REFERENCES `users` (`userId`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `resolutions`
--

DROP TABLE IF EXISTS `resolutions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resolutions` (
  `ResolutionId` int(11) NOT NULL AUTO_INCREMENT,
  `MeetingId` int(11) NOT NULL,
  `Title` varchar(200) NOT NULL,
  `Description` text DEFAULT NULL,
  `ResponsiblePerson` varchar(150) DEFAULT NULL,
  `Deadline` date DEFAULT NULL,
  `Status` enum('pending','in_progress','completed','cancelled') DEFAULT 'pending',
  `Createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ResolutionId`),
  KEY `fk_resolution_meeting` (`MeetingId`),
  CONSTRAINT `fk_resolution_meeting` FOREIGN KEY (`MeetingId`) REFERENCES `meetings` (`Meeting_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sms_recipients`
--

DROP TABLE IF EXISTS `sms_recipients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_recipients` (
  `RecipientId` int(11) NOT NULL AUTO_INCREMENT,
  `Sms_id` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `Provider_id` varchar(100) DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `status` enum('pending','sent','failed') DEFAULT 'pending',
  PRIMARY KEY (`RecipientId`),
  UNIQUE KEY `unique_sms_recipient` (`Sms_id`,`UserId`),
  KEY `fk_recipient_user` (`UserId`),
  CONSTRAINT `fk_recipient_sms` FOREIGN KEY (`Sms_id`) REFERENCES `bulk_sms` (`Sms_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_recipient_user` FOREIGN KEY (`UserId`) REFERENCES `users` (`userId`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `Firstname` varchar(50) NOT NULL,
  `Lastname` varchar(50) NOT NULL,
  `phoneNo` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','chairman','secretary','citizen') NOT NULL DEFAULT 'citizen',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `phoneNo` (`phoneNo`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'village_management_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-12 15:22:24
