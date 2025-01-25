-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 19, 2024 at 08:40 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gamecafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `uname`, `email`, `pass`) VALUES
(4, 'abc', 'abc@abc.in', '$2y$10$TtCHPEyBlZrehf7sKoJL0OWKX6vpA1vWJS6x/pGHMtn2AOA7cP3XG'),
(6, 'Harshal', 'harsh@gammers.in', '$2y$10$sKGCDVcki1cJ3NTspnJ5ROveFTcuCwGT.Rx82xjkwGZCy.s8TAo16'),
(5, 'Hemanshu', 'hemanshu@gammers.in', '$2y$10$OMLyBWjJv2s9FTNHYaRAWe7SjR/ROR4B8i3XSONJ0xDvWSRZ2SuTq');

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

DROP TABLE IF EXISTS `bill`;
CREATE TABLE IF NOT EXISTS `bill` (
  `bill_id` int NOT NULL AUTO_INCREMENT,
  `payment_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `bill_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`bill_id`),
  KEY `payment_id` (`payment_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
CREATE TABLE IF NOT EXISTS `devices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `device_name` varchar(255) NOT NULL,
  `device_type` enum('Computer','PlayStation','GamingPhone') NOT NULL,
  `status` enum('Vacant','Occupied') DEFAULT 'Vacant',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) NOT NULL,
  `description` text,
  `user_id` int DEFAULT NULL,
  `reserved_at` datetime DEFAULT NULL,
  `reservation_duration` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `device_name`, `device_type`, `status`, `created_at`, `ip_address`, `description`, `user_id`, `reserved_at`, `reservation_duration`) VALUES
(3, 'abc', 'Computer', 'Vacant', '2024-09-30 14:35:41', '192.123.123.12', 'asdfghjk', NULL, NULL, NULL),
(5, 'Play Station 5', 'PlayStation', 'Vacant', '2024-09-30 14:35:41', '192.236.123.2', 'Play station with 32 inch oled monitor', NULL, NULL, NULL),
(6, 'PQR', 'Computer', 'Vacant', '2024-09-30 14:35:41', '192.192.192.3', 'computer with 28 inch olsed monitor and with RTX 4080', 1, '2024-10-10 13:43:24', 2),
(7, 'Assus Rog Phone 3', 'GamingPhone', 'Occupied', '2024-09-30 14:35:41', '192.192.192.1', 'gaming phone with 8 GB ram', 4, '2024-10-19 09:56:54', 1),
(8, 'abc', 'Computer', 'Vacant', '2024-09-30 14:35:41', '192.123.123.12', 'asdfghjk', NULL, NULL, NULL),
(9, 'PQR', 'Computer', 'Vacant', '2024-09-30 14:35:41', '192.192.192.3', 'computer with 28 inch olsed monitor and with RTX 4080', NULL, NULL, NULL),
(11, 'Assus Rog Phone 5', 'GamingPhone', 'Vacant', '2024-09-30 14:35:41', '192.192.192.4', 'gaming phone with 8 GB ram', 0, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
CREATE TABLE IF NOT EXISTS `games` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `genres` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `genres`, `description`, `image`) VALUES
(1, 'Mortal Kombat Onslaught', 'Action', 'A mobile RPG game in the Mortal Kombat universe that combines strategy and fighting, with players building teams of fighters to battle enemies.', 'img/mk1.jpg'),
(2, 'Assassin\'s Creed', 'Adventure', 'An action-adventure game set in various historical periods, where players control assassins battling Templars using stealth, combat, and parkour skills.', 'img/ac.jpg'),
(3, 'Batman Arkham Knight', 'Action', 'The final chapter in the Arkham series, where Batman faces the Scarecrow and a mysterious enemy called the Arkham Knight in an open-world Gotham City.', 'img/bm.jpg'),
(4, 'Injustice 2', 'Action', 'A fighting game featuring DC superheroes and villains. It continues the story of Injustice: Gods Among Us, where Batman and allies attempt to rebuild society after Superman\'s regime.', 'img/ij2.jpg'),
(5, 'Spider-Man 2', 'Action', 'An action-adventure game based on the Marvel superhero, focusing on open-world exploration of New York City, web-swinging, and fighting villains.', 'img/sm.png'),
(6, 'Call of Duty Modern Warfare', 'Shooter', 'A first-person shooter game that reboots the Modern Warfare subseries, featuring modern military combat and a gripping, realistic campaign.', 'img/cod4.jpg'),
(7, 'Battlefield 4', 'Shooter', 'A first-person shooter set in modern warfare scenarios, known for its large-scale multiplayer battles, destructible environments, and vehicular combat.', 'img/bf.jpg'),
(8, 'Prince of Persia Forgotten Sands', 'Adventure', 'An action-adventure game where the Prince uses time manipulation and elemental powers to fight enemies and solve puzzles.', 'img/pp.jpg'),
(9, 'Need for Speed Most Wanted', 'Racing', 'An open-world racing game where players compete in high-speed races while avoiding the police in a variety of customizable cars.', 'img/nfs.jpg'),
(10, 'Cricket Ashes', 'Sports', 'A cricket simulation game focused on the famous Ashes series between England and Australia, offering realistic gameplay and matches.', 'img/c22.jpg'),
(11, 'Football 2023', 'Sports', 'A soccer/football simulation game that allows players to experience football leagues and tournaments with up-to-date teams and realistic gameplay.', 'img/fb.jpg'),
(12, 'Forza Street', 'Racing', 'A mobile racing game that focuses on short, fast-paced races on the streets, with an emphasis on collecting and upgrading cars.', 'img/fs.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `upi_ref_id` varchar(255) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmt`
--

DROP TABLE IF EXISTS `tmt`;
CREATE TABLE IF NOT EXISTS `tmt` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tournament_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tournament_id` (`tournament_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tmt`
--

INSERT INTO `tmt` (`id`, `uid`, `name`, `phone`, `email`, `tournament_id`) VALUES
(7, '123456789', 'Harshal', '9876543212', 'desaiharshal@gmail.com', 5),
(2, '42530125', 'Hemanshu Gajare', '9373045003', 'gajareharsh810@gmail.com', 1),
(6, '123456789', 'Harshal', '9874563211', 'desaiharshal@gmail.com', 3),
(5, '123456789', 'Harshal', '9874563211', 'desaiharshal@gmail.com', 3),
(8, '123456789', 'Harshal', '9876543212', 'desaiharshal@gmail.com', 5),
(9, '123456789', 'Harshal', '9876543212', 'desaiharshal@gmail.com', 6),
(10, '123456789', 'Harshal', '98765412', 'desaiharshal@gmail.com', 6),
(11, '123456789', 'Harshal', '9876543212', 'desaiharshal@gmail.com', 7),
(12, '123456789', 'Harshal', '9876543212', 'desaiharshal@gmail.com', 7),
(13, '123456789', 'Harshal', '9876543212', 'desaiharshal@gmail.com', 9),
(14, '123456789', 'Harshal', '9876543212', 'desaiharshal@gmail.com', 10);

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

DROP TABLE IF EXISTS `tournaments`;
CREATE TABLE IF NOT EXISTS `tournaments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `game_id` int NOT NULL,
  `tournament_date` date NOT NULL,
  `tournament_time` time NOT NULL,
  `mode` varchar(10) NOT NULL,
  `entry_fee` decimal(10,2) NOT NULL,
  `player_count` int NOT NULL,
  `prize_pool_1st` decimal(10,2) NOT NULL,
  `prize_pool_2nd` decimal(10,2) NOT NULL,
  `prize_pool_3rd` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tournaments`
--

INSERT INTO `tournaments` (`id`, `game_id`, `tournament_date`, `tournament_time`, `mode`, `entry_fee`, `player_count`, `prize_pool_1st`, `prize_pool_2nd`, `prize_pool_3rd`, `created_at`) VALUES
(6, 1, '2024-10-03', '11:23:00', 'online', 250.00, 10, 0.00, 0.00, 0.00, '2024-10-03 03:49:47'),
(5, 6, '2024-10-01', '14:52:00', 'online', 250.00, 20, 0.00, 0.00, 0.00, '2024-10-01 08:20:09'),
(3, 6, '2024-09-28', '10:12:00', 'online', 250.00, 50, 2500.00, 2000.00, 1500.00, '2024-09-27 15:48:12'),
(4, 1, '2024-09-28', '11:20:00', 'online', 250.00, 50, 2500.00, 2000.00, 1500.00, '2024-09-27 15:48:55'),
(7, 1, '2024-10-04', '18:04:00', 'online', 500.00, 10, 0.00, 0.00, 0.00, '2024-10-04 09:31:29'),
(8, 1, '2024-10-10', '13:50:00', 'online', 250.00, 10, 0.00, 0.00, 0.00, '2024-10-10 08:19:37'),
(9, 6, '2024-10-25', '12:02:00', 'online', 2500.00, 10, 0.00, 0.00, 0.00, '2024-10-15 06:32:59'),
(10, 7, '2024-10-25', '10:00:00', 'online', 325.00, 4, 0.00, 0.00, 0.00, '2024-10-19 04:33:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uname` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` bigint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uname`, `pass`, `email`, `phone`) VALUES
(1, 'Hemanshu', '$2y$10$EL7iPc.5wN6vvOYaTRGWAOTWK/x3G1/fpU.m/4P/uOJmEfGaDZuUa', 'gajarehemanshu249@gamil.com', 9373045003),
(4, 'Harshal', '$2y$10$h1yBr7fIwmGuKBB5U1bkX.SpXEOBPpE8YpyJi0ybF9j48rUeTIscO', 'desaiharshal@gmail.com', 9876543212),
(5, 'asd', '$2y$10$R5XnJUsrK1Ze.b8Ky7/T/eUB8gUbwlnLLDWzj7MX2R0wfNDQCQrRm', 'asd@gmail.com', 8795246312),
(7, 'Harsh', '$2y$10$r3c9K7mE06JSeoaM6PMp5.NgCHilVhdRIRP7eBtyib2ZTeS3Ib642', 'gajareharsh810@gmail.com', 8975632145),
(8, 'abc', '$2y$10$k4GAIHBat2V0zXyYzeumSe9Z5ioARQqV.lfmnu4/3jYtq5F9wqEP2', 'abc@abc.in', 8752134692);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
