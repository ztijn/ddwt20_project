-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 18, 2021 at 02:10 PM
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
-- Database: `ddwt20_project`
--
CREATE DATABASE IF NOT EXISTS `ddwt20_project` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ddwt20_project`;

-- --------------------------------------------------------

--
-- Table structure for table `leases`
--

CREATE TABLE `leases` (
  `lease_id` int(11) NOT NULL,
  `room` int(11) NOT NULL,
  `tenant` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `leases`
--

INSERT INTO `leases` (`lease_id`, `room`, `tenant`, `start_date`, `end_date`) VALUES
(1, 4, 3, '2020-01-18', '2021-01-18'),
(5, 7, 5, '2021-01-18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender`, `receiver`, `datetime`, `message`) VALUES
(8, 3, 4, '2021-01-18 14:03:49', 'Hallo, Ik zou graag deze kamer willen huren!'),
(9, 3, 4, '2021-01-18 14:04:46', 'Ik zie nu dat ik in meerdere kamers van u Geinteresserd ben. Zowel de kamer aan de Marktstraat als de studio aan de Aweg lijken mij interessant'),
(10, 3, 6, '2021-01-18 14:05:18', 'Bonjour'),
(11, 3, 6, '2021-01-18 14:05:52', 'I would like to rent the Eiffeltower'),
(12, 5, 4, '2021-01-18 14:06:43', 'Hi, Are both the rooms still available?'),
(13, 5, 6, '2021-01-18 14:07:41', 'Buongiorno'),
(14, 5, 6, '2021-01-18 14:08:05', 'posso affittare la torre di pisa?'),
(15, 6, 5, '2021-01-18 14:08:43', 'si'),
(16, 6, 3, '2021-01-18 14:09:32', 'you know it is 12000 per day/night, not per month, right? it is only for events');

-- --------------------------------------------------------

--
-- Table structure for table `optins`
--

CREATE TABLE `optins` (
  `optin_id` int(11) NOT NULL,
  `room` int(11) NOT NULL,
  `tenant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `optins`
--

INSERT INTO `optins` (`optin_id`, `room`, `tenant`) VALUES
(6, 4, 3),
(7, 3, 3),
(8, 5, 3),
(11, 6, 3),
(12, 3, 5),
(13, 4, 5),
(14, 5, 5),
(15, 6, 5);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `status` enum('available','unavailable') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `owner`, `address`, `type`, `price`, `size`, `status`) VALUES
(3, 4, 'Aweg 5-205', 'Studio', 800, 300, 'available'),
(4, 4, 'Marktstraat 9a', 'student room', 354, 16, 'available'),
(5, 6, 'Winkelstraat 5, 1234AB Groningen', 'Villa', 1500, 350, 'available'),
(6, 6, 'Champ de Mars, 5 Avenue Anatole France, 75007 Paris', 'Toren', 12000, 20, 'available'),
(7, 6, 'Piazza del Duomo, 56126 Pisa PI, Italië', 'Toren', 5600, 57, 'unavailable');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('owner','tenant') NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `biography` text NOT NULL,
  `stud_prof` varchar(255) NOT NULL,
  `language` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `full_name`, `birth_date`, `biography`, `stud_prof`, `language`, `email`, `phone`) VALUES
(3, 'tenant1', '$2y$10$KGRrpDsZCbMoOjfhBt.zh.B41AmE.RjjDev9pcVzeoZ4FHPZkEiXO', 'tenant', 'Stijn Wijnen', '1997-02-25', 'i am a tenant', 'Information Science', 'Dutch', 's.o.wijnen@student.rug.nl', '0612345678'),
(4, 'owner1', '$2y$10$WyXtSmv7EFnMXon4AG3CzeYJo6niyvw7BIuOuGKD2ckFdU5rInf2i', 'owner', 'Ztijn Vino', '1990-01-01', 'I am an owner', 'huisbaas', 'Dutch', 's.o.wijnen@student.rug.nl', '0687654321'),
(5, 'tenant2', '$2y$10$nKKwnyoGjvsCuhj7PDBLiu4L6uRkDuohs4ZZkhdpaaHzEmhvV8Asq', 'tenant', 'Jan Janssen', '1990-02-02', 'I am also a tenant!', 'Unemployed', 'English', 'jj@yahoo.com', '0622222222'),
(6, 'owner2', '$2y$10$oAEcFdeKvfAK2h0CCpf/CuXBeuWZpdPm1sWPGxjITVgoa.LIzq54K', 'owner', 'Berend Brokaap', '1965-06-14', 'I love money', 'Mafia Boss', 'Italian', 'bb@gmail.com', '0622222222');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `leases`
--
ALTER TABLE `leases`
  ADD PRIMARY KEY (`lease_id`),
  ADD KEY `room` (`room`),
  ADD KEY `tenant` (`tenant`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `receiver` (`receiver`),
  ADD KEY `sender` (`sender`);

--
-- Indexes for table `optins`
--
ALTER TABLE `optins`
  ADD PRIMARY KEY (`optin_id`),
  ADD KEY `room` (`room`),
  ADD KEY `tenant` (`tenant`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `owner` (`owner`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `leases`
--
ALTER TABLE `leases`
  MODIFY `lease_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `optins`
--
ALTER TABLE `optins`
  MODIFY `optin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leases`
--
ALTER TABLE `leases`
  ADD CONSTRAINT `leases_ibfk_1` FOREIGN KEY (`room`) REFERENCES `rooms` (`room_id`),
  ADD CONSTRAINT `leases_ibfk_2` FOREIGN KEY (`tenant`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`receiver`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `optins`
--
ALTER TABLE `optins`
  ADD CONSTRAINT `optins_ibfk_1` FOREIGN KEY (`room`) REFERENCES `rooms` (`room_id`),
  ADD CONSTRAINT `optins_ibfk_2` FOREIGN KEY (`tenant`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
