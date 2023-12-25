-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 25, 2023 at 12:35 AM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecom1_project`
--
CREATE DATABASE IF NOT EXISTS ecom1_project;

USE ecom1_project;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
CREATE TABLE IF NOT EXISTS `address` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `street_name` varchar(255) NOT NULL,
  `street_nb` int NOT NULL,
  `city` varchar(40) NOT NULL,
  `province` varchar(40) NOT NULL,
  `zip_code` varchar(6) NOT NULL,
  `country` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `street_name`, `street_nb`, `city`, `province`, `zip_code`, `country`) VALUES
(39, 'rosedalle', 12, 'montreal', 'quebec', 'h43', 'Canada'),
(40, 'walk', 13, 'montreal', 'quebec', 'f5v', 'canada');

-- --------------------------------------------------------

--
-- Table structure for table `order_has_product`
--

DROP TABLE IF EXISTS `order_has_product`;
CREATE TABLE IF NOT EXISTS `order_has_product` (
  `quantity` int NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `order_id` bigint NOT NULL,
  `product_id` bigint NOT NULL,
  PRIMARY KEY (`product_id`,`order_id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_has_product`
--

INSERT INTO `order_has_product` (`quantity`, `price`, `order_id`, `product_id`) VALUES
(1, '3.00', 113, 32),
(67878, '3.00', 111, 32),
(11, '3.00', 116, 26),
(10, '777.00', 116, 10);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `img_url` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `quantity`, `price`, `img_url`, `description`) VALUES
(26, 'CAHIER', 4, '3.00', '65864e7fa0a1bCAHIER.jpg', 'POUR LES ETUDIANTS '),
(10, 'hawai', 565, '777.00', 'hawaii.png', '23'),
(28, 'VIANDE HACHEE', 45, '14.00', '65864ef527faaVIANDE.jpg', 'PAS DES DETAILS POUR L\'INSTANT'),
(27, 'poulet', 4, '2.00', '65864ec7cfa14POULET.jpg', 'POULET DU MAROC'),
(29, 'PIZZA', 23, '20.00', '65864f13d4f88PIZZA.jpg', 'PIZZA PIZZA'),
(30, 'FROMAGE ', 75, '7.00', '65864f412091ePIZZAjpg.jpg', 'FROMAGEE'),
(31, 'tonik', 4, '2.00', '65864f5b54030BIMO.jpg', 'bimo'),
(32, 'golden', 34, '3.00', '65864f7473f0bGOLDEN.jpg', 'bimo');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `description`) VALUES
(1, 'SuperAdmin', 'role super administrateur'),
(3, 'client', 'role client');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `billing_address_id` bigint NOT NULL,
  `shipping_address_id` bigint NOT NULL,
  `token` varchar(255) NOT NULL,
  `role_id` bigint NOT NULL,
  `user_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`),
  KEY `role_id` (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `pwd`, `fname`, `lname`, `billing_address_id`, `shipping_address_id`, `token`, `role_id`, `user_name`) VALUES
(98, 'superadmin@admin.ca', '$2y$10$pvYufmdKSn/3teuX0DWSzud7Z5kZpyKoGF1810Stjaw1kgIefh/06', '', '', 0, 0, '', 1, 'superadmin'),
(116, 'saadboumhdi9@gmail.com', '$2y$10$mFgQVU6s8amlOSKoD5FvJOcWtJWUu0OPGDUBBKLKqcIU2Gm3PTRhW', 'boumahdi', 'amri', 40, 39, '', 3, 'saad'),
(118, 'saadboumhdi9@gmail.com', '$2y$10$rKYTG1qJHWDQRevef4uaIuu7W.WLXZND5HTimenb/0cGwqGWqGMhO', 'boumahdi', 'amri', 0, 0, '', 2, 'baba');

-- --------------------------------------------------------

--
-- Table structure for table `user_order`
--

DROP TABLE IF EXISTS `user_order`;
CREATE TABLE IF NOT EXISTS `user_order` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `ref` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `user_id` bigint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_order`
--

INSERT INTO `user_order` (`id`, `ref`, `date`, `total`, `user_id`) VALUES
(10, '65863fad92a85', '2023-12-23', '302.50', 105),
(11, '6588cc4dbfbf0', '2023-12-25', '6993.00', 116);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
