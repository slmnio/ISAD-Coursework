-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: proj-mysql.uopnet.plymouth.ac.uk
-- Generation Time: Jan 10, 2020 at 12:51 PM
-- Server version: 8.0.16
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `isad251_scammack`
--
CREATE DATABASE IF NOT EXISTS `isad251_scammack` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `isad251_scammack`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `GetLowStock`$$
CREATE DEFINER=`ISAD251_SCammack`@`%` PROCEDURE `GetLowStock` (`stockLimit` INT(11))  SELECT id, name, quantity FROM items
	WHERE items.quantity < stockLimit
	AND items.enabled = TRUE$$

DROP PROCEDURE IF EXISTS `StockInQueue`$$
CREATE DEFINER=`ISAD251_SCammack`@`%` PROCEDURE `StockInQueue` ()  SELECT order_items.item_id, items.name, SUM(order_items.quantity) AS "Total to dispatch"
    FROM order_items
    LEFT JOIN items ON items.id = order_items.item_id
    GROUP BY order_items.item_id$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`) VALUES
(5, 'Drinks', 'drinks'),
(6, 'Snacks', 'snacks'),
(7, 'Alcohol-free', 'alcohol-free'),
(8, 'Desserts', 'desserts');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `category_id` int(11) DEFAULT NULL,
  `cost_pence` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `description`, `category_id`, `cost_pence`, `quantity`, `enabled`) VALUES
(2, 'Broweiser', 'Delicious beer: 2.5%', 5, 365, 28, 1),
(3, 'Coke', 'Tasty & refreshing', 5, 160, 34, 1),
(4, 'Jack Daniels', 'Pour one out', 5, 320, 1, 1),
(5, 'Vodka Shot', '65% ABV. May include Vodka', 5, 2500, 20, 1),
(6, 'Mountain Dew', 'To improve your typing', 5, 195, 12, 0),
(7, 'Crisps', 'crunchy...', 6, 75, 2, 1),
(8, 'Peanuts', 'very crunchy...', 6, 125, -12, 1),
(9, 'Orange juice', '(aka OJ)', 7, 275, 7, 1),
(10, 'Waffle', 'with vanilla ice-cream', 8, 675, 11, 1),
(12, 'Chocolate Milk', 'Guilty pleasure. (you\'re epic)', 7, 75, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `table_number` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `table_number`, `updated_at`) VALUES
(6, 1, '20', '2020-01-10 03:50:33'),
(7, 1, '21', '2020-01-10 03:50:57'),
(8, 1, NULL, '2020-01-10 03:52:26'),
(9, 1, '25', '2020-01-10 03:52:42'),
(10, 1, '550', '2020-01-10 03:53:52'),
(13, 1, '40', '2020-01-10 04:57:48'),
(15, 1, '5', '2020-01-10 05:19:28'),
(18, 2, '18', '2020-01-10 07:34:30'),
(19, 1, '9', '2020-01-10 08:44:56');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_id`, `item_id`, `quantity`) VALUES
(6, 8, 1),
(6, 7, 1),
(6, 3, 2),
(7, 8, 1),
(7, 7, 1),
(7, 3, 1),
(8, 8, 1),
(8, 7, 1),
(8, 3, 1),
(9, 8, 1),
(9, 7, 1),
(9, 3, 1),
(9, 4, 2),
(10, 8, 1),
(10, 7, 1),
(10, 3, 1),
(10, 4, 2),
(13, 8, 3),
(15, 10, 4),
(18, 2, 5),
(18, 3, 1),
(19, 8, 1);

--
-- Triggers `order_items`
--
DROP TRIGGER IF EXISTS `updateStock`;
DELIMITER $$
CREATE TRIGGER `updateStock` BEFORE INSERT ON `order_items` FOR EACH ROW UPDATE items
    SET items.quantity = items.quantity - new.quantity
    WHERE items.id = new.item_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `remember_token`, `is_admin`) VALUES
(1, 'John Admin', 'jadmin', 'password', NULL, 1),
(2, 'Jill Customer', 'jcustomer', 'password', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD KEY `order_id` (`order_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
