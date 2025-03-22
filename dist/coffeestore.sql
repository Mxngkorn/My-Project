-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2025 at 11:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coffeestore`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `CartId` int(11) NOT NULL,
  `ProductId` int(11) DEFAULT NULL,
  `Quantity` int(11) NOT NULL,
  `UserId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryId` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `CreateDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryId`, `Name`, `CreateDate`) VALUES
(1, 'ชา', '2024-11-26 03:10:02'),
(2, 'กาแฟ', '2024-11-26 03:10:11'),
(3, 'โซดา', '2025-02-09 10:49:10');

-- --------------------------------------------------------

--
-- Table structure for table `isorder`
--

CREATE TABLE `isorder` (
  `OrderDetailId` int(11) NOT NULL,
  `OrderNo` varchar(50) NOT NULL,
  `ProductId` int(11) DEFAULT NULL,
  `Description` varchar(30) DEFAULT NULL,
  `Quantity` int(11) NOT NULL,
  `UserId` int(11) DEFAULT NULL,
  `Status` varchar(50) NOT NULL,
  `PaymentId` int(11) DEFAULT NULL,
  `OrderDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `isorder`
--

INSERT INTO `isorder` (`OrderDetailId`, `OrderNo`, `ProductId`, `Description`, `Quantity`, `UserId`, `Status`, `PaymentId`, `OrderDate`) VALUES
(229, 'ORD1741591114', 6, 'ระดับความหวาน: หวานมาก', 10, 3, 'เสร็จสิ้น', 167, '2025-03-10 07:18:34'),
(230, 'ORD1741591284', 8, 'ระดับความหวาน: หวานปกติ', 10, 3, 'เสร็จสิ้น', 168, '2025-03-10 07:21:24'),
(231, 'ORD1741594506', 6, 'ระดับความหวาน: หวานมาก', 10, 1, 'เสร็จสิ้น', 169, '2025-03-10 08:15:06'),
(232, 'ORD1741594506', 15, 'ระดับความหวาน: หวานปกติ', 10, 1, 'เสร็จสิ้น', 169, '2025-03-10 08:15:06'),
(233, 'ORD1741594506', 12, 'ระดับความหวาน: หวานมาก', 10, 1, 'เสร็จสิ้น', 169, '2025-03-10 08:15:06'),
(234, 'ORD1741597656', 6, 'ระดับความหวาน: ไม่หวาน', 1, 1, 'เสร็จสิ้น', 170, '2025-03-10 09:07:36'),
(235, 'ORD1741678207', 6, 'ระดับความหวาน: ไม่หวาน', 5, 37, 'เสร็จสิ้น', 171, '2025-03-11 07:30:07'),
(236, 'ORD1741682146', 6, 'ระดับความหวาน: หวานมาก', 3, 3, 'เสร็จสิ้น', 172, '2025-03-11 08:35:46'),
(237, 'ORD1741851039', 8, 'ระดับความหวาน: ไม่หวาน', 5, 1, 'เสร็จสิ้น', 173, '2025-03-13 07:30:39'),
(238, 'ORD1741933326', 8, 'ระดับความหวาน: หวานมาก', 5, 1, 'เสร็จสิ้น', 174, '2025-03-14 06:22:06'),
(239, 'ORD1741933552', 6, 'ระดับความหวาน: หวานมาก', 1, 1, 'เสร็จสิ้น', 175, '2025-03-14 06:25:52'),
(240, 'ORD1741933596', 15, 'ระดับความหวาน: ไม่หวาน', 6, 1, 'กำลังทำ', 176, '2025-03-14 06:26:36'),
(241, 'ORD1741944696', 6, 'ระดับความหวาน: หวานปกติ', 6, 1, 'กำลังทำ', 177, '2025-03-14 09:31:36'),
(242, 'ORD1741944729', 6, 'ระดับความหวาน: ไม่หวาน', 3, 1, 'กำลังทำ', 178, '2025-03-14 09:32:09'),
(243, 'ORD1741945006', 12, 'ระดับความหวาน: หวานปกติ', 1, 1, 'กำลังทำ', 179, '2025-03-14 09:36:46'),
(244, 'ORD1741945457', 15, 'ระดับความหวาน: หวานปกติ', 1, 1, 'กำลังทำ', 180, '2025-03-14 09:44:17'),
(245, 'ORD1742185284', 6, 'ระดับความหวาน: หวานปกติ', 1, 1, 'กำลังทำ', 181, '2025-03-17 04:21:24'),
(246, 'ORD1742187851', 8, 'ระดับความหวาน: หวานมาก', 1, 1, 'กำลังทำ', 182, '2025-03-17 05:04:11');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `PaymentId` int(11) NOT NULL,
  `UserId` int(11) DEFAULT NULL,
  `PaymentMode` varchar(50) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `PaymentStatus` varchar(50) DEFAULT NULL,
  `PaymentDate` timestamp NULL DEFAULT current_timestamp(),
  `SlipImageUrl` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`PaymentId`, `UserId`, `PaymentMode`, `Amount`, `PaymentStatus`, `PaymentDate`, `SlipImageUrl`) VALUES
(167, 3, 'เงินสด', 200.00, 'ชำระเรียบร้อย', '2025-03-10 07:18:34', ''),
(168, 3, 'เงินโอน', 200.00, 'ชำระเรียบร้อย', '2025-03-10 07:21:24', 'uploads_slip/1741591284_S__33439759.jpg'),
(169, 1, 'เงินสด', 600.00, 'ชำระเรียบร้อย', '2025-03-10 08:15:06', ''),
(170, 1, 'เงินสด', 20.00, 'ชำระเรียบร้อย', '2025-03-10 09:07:36', ''),
(171, 37, 'เงินโอน', 100.00, 'ชำระเรียบร้อย', '2025-03-11 07:30:07', 'uploads_slip/1741678207_S__33439759.jpg'),
(172, 3, 'เงินสด', 60.00, 'ชำระเรียบร้อย', '2025-03-11 08:35:46', ''),
(173, 1, 'เงินสด', 100.00, 'ชำระเรียบร้อย', '2025-03-13 07:30:39', ''),
(174, 1, 'เงินสด', 100.00, 'ชำระเรียบร้อย', '2025-03-14 06:22:06', ''),
(175, 1, 'เงินสด', 20.00, 'ชำระเรียบร้อย', '2025-03-14 06:25:52', ''),
(176, 1, 'เงินโอน', 120.00, 'ชำระเรียบร้อย', '2025-03-14 06:26:36', 'uploads_slip/1741933596_S__33439759.jpg'),
(177, 1, 'เงินสด', 120.00, 'ยังไม่ชำระ', '2025-03-14 09:31:36', ''),
(178, 1, 'เงินโอน', 60.00, 'ชำระเรียบร้อย', '2025-03-14 09:32:09', 'uploads_slip/1741944729_S__33439759.jpg'),
(179, 1, 'เงินสด', 20.00, 'ยังไม่ชำระ', '2025-03-14 09:36:46', ''),
(180, 1, 'เงินโอน', 20.00, 'ชำระเรียบร้อย', '2025-03-14 09:44:17', 'uploads_slip/1741945457_S__33439759.jpg'),
(181, 1, 'เงินสด', 20.00, 'ยังไม่ชำระ', '2025-03-17 04:21:24', ''),
(182, 1, 'เงินโอน', 20.00, 'ชำระเรียบร้อย', '2025-03-17 05:04:11', 'uploads_slip/1742187851_S__33439759.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductId` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` varchar(30) DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `ImageUrl` varchar(255) DEFAULT NULL,
  `CategoryId` int(11) DEFAULT NULL,
  `IsActive` tinyint(1) DEFAULT 1,
  `CreateDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductId`, `Name`, `Description`, `Price`, `Quantity`, `ImageUrl`, `CategoryId`, `IsActive`, `CreateDate`) VALUES
(6, 'ชาเขียว', NULL, 20.00, 0, 'uploads/ชาเขียว.png', 1, 1, '2025-01-22 17:42:32'),
(8, 'โกโก้', NULL, 20.00, 0, 'uploads/โกโก้เย็น.jpg', 1, 1, '2025-01-22 17:51:49'),
(12, 'แดงโซดา', NULL, 20.00, 0, 'uploads_products/1739099528_LINE_ALBUM_รูปร้านน้ำ_250209_3.jpg', 3, 0, '2025-02-09 11:12:08'),
(15, 'กาแฟโบราณ', NULL, 20.00, 0, 'uploads_products/1741591943_กาแฟ.jpg', 2, 0, '2025-03-10 07:32:23');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `StockId` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `ImageUrl` varchar(255) DEFAULT NULL,
  `CreateDate` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`StockId`, `Name`, `Quantity`, `ImageUrl`, `CreateDate`) VALUES
(1, 'ผงชาเขียว', 8, 'uploads_stock/1737908020_a8sibk.jpg', '2025-01-26 23:13:40'),
(2, 'ผงโกโก้', 8, 'uploads_stock/1738599883_socii7.jpg', '2025-02-03 23:24:43'),
(8, 'ผงชาไทย', 10, 'uploads_stock/1738600428_ผงชาไทย.jpeg', '2025-02-03 23:33:48'),
(11, 'ผงกาแฟ', 3, 'uploads_stock/1738600927_bt.jpg', '2025-02-03 23:42:07'),
(14, 'ผงชามะนาว', 7, 'uploads_stock/1741592222_ชามะนาว.jpg', '2025-03-10 14:37:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserId` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Lastname` varchar(50) DEFAULT NULL,
  `Mobile` varchar(20) DEFAULT NULL,
  `Email` varchar(50) NOT NULL,
  `Department` varchar(50) DEFAULT NULL,
  `Password` varchar(50) NOT NULL,
  `ImageUrl` varchar(255) DEFAULT NULL,
  `CreateDate` datetime DEFAULT current_timestamp(),
  `Row` varchar(20) NOT NULL DEFAULT 'member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserId`, `Name`, `Lastname`, `Mobile`, `Email`, `Department`, `Password`, `ImageUrl`, `CreateDate`, `Row`) VALUES
(1, 'korn', 'poliam', '0971234567', 'korn@gmail.com', 'เทคโนโลยีคอม', 'korm2546', 'uploads/1740323158_แมว.jpg', '2024-11-20 09:42:24', 'สมาชิก'),
(3, 'admin', 'admin', '515151', 'admin@gmail.com', 'admin', 'admin', 'uploads/1740324053_แมว.jpg', '2025-01-16 17:54:32', 'ผู้ดูแล'),
(5, 'mogkorn', 'popo', '096457894', 'mongkorn@gmail.com', 'เทคนิคคอม', 'korn2546', 'dist/img/default-user.jpg', '2025-01-24 15:16:09', 'สมาชิก'),
(37, 'mogkorn', 'poliam2', '6441896484', 'test@gmail.com', 'เทคนิคคอม', '123456789', 'uploads/67c5bc333397b.jpg', '2025-03-03 21:26:07', 'พนักงาน');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`CartId`),
  ADD KEY `ProductId` (`ProductId`),
  ADD KEY `UserId` (`UserId`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryId`);

--
-- Indexes for table `isorder`
--
ALTER TABLE `isorder`
  ADD PRIMARY KEY (`OrderDetailId`),
  ADD KEY `ProductId` (`ProductId`),
  ADD KEY `UserId` (`UserId`),
  ADD KEY `PaymentId` (`PaymentId`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`PaymentId`),
  ADD KEY `UserId` (`UserId`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductId`),
  ADD KEY `CategoryId` (`CategoryId`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`StockId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `CartId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `isorder`
--
ALTER TABLE `isorder`
  MODIFY `OrderDetailId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `PaymentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `StockId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`ProductId`) REFERENCES `products` (`ProductId`),
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`UserId`) REFERENCES `users` (`UserId`);

--
-- Constraints for table `isorder`
--
ALTER TABLE `isorder`
  ADD CONSTRAINT `isorder_ibfk_1` FOREIGN KEY (`ProductId`) REFERENCES `products` (`ProductId`),
  ADD CONSTRAINT `isorder_ibfk_2` FOREIGN KEY (`UserId`) REFERENCES `users` (`UserId`),
  ADD CONSTRAINT `isorder_ibfk_3` FOREIGN KEY (`PaymentId`) REFERENCES `payments` (`PaymentId`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `users` (`UserId`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`CategoryId`) REFERENCES `categories` (`CategoryId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
