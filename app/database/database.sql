SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `coffeeshop_db` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `coffeeshop_db`;

-- ========================================================
-- 1. admin_users (The Owners/Managers)
-- ========================================================
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(191) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password is 'admin1234'
INSERT INTO `admin_users` (`email`, `password_hash`, `name`) VALUES
('admin@coffeeshop.com', '$2y$10$u/ERjvS5U/sZedD4TOf/uOSTKTRyzr/iRUeHsD5hi31lUsVxZ3tge', 'Shop Manager');

-- ========================================================
-- 2. staff (Baristas, Waiters, Cashiers)
-- ========================================================
DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL, -- e.g., 'Barista', 'Cashier', 'Waiter'
  `phone` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Example Staff (Password is 'staff1234')
INSERT INTO `staff` (`username`, `password_hash`, `full_name`, `role`, `phone`) VALUES
('sokha_barista', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'Sokha Mean', 'Barista', '012345678');

-- ========================================================
-- 3. menu_items
-- ========================================================
DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE `menu_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price_usd` decimal(10,2) NOT NULL,
  `price_khr` decimal(15,0) NOT NULL,
  `image_path` varchar(255),
  `is_available` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- 4. orders (Linked to Staff instead of Customer)
-- ========================================================
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `staff_id` int NOT NULL, -- The staff who took the order
  `order_type` varchar(50) NOT NULL, -- Dine-in, Takeaway
  `status` varchar(50) DEFAULT 'Pending',
  `total_usd` decimal(10,2) NOT NULL,
  `total_khr` decimal(15,0) NOT NULL,
  `table_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- 5. order_items
-- ========================================================
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `menu_item_id` int NOT NULL,
  `quantity` int NOT NULL,
  `item_price_usd` decimal(10,2) NOT NULL,
  `item_price_khr` decimal(15,0) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
