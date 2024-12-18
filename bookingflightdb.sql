-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th12 15, 2024 lúc 04:22 AM
-- Phiên bản máy phục vụ: 8.3.0
-- Phiên bản PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `bookingflightdb`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE DATABASE bookingflightdb;

USE bookingflightdb;


CREATE TABLE IF NOT EXISTS `bookings` (
  `BookingID` int NOT NULL AUTO_INCREMENT,
  `UserID` int NOT NULL,
  `FlightID` int NOT NULL,
  `SeatsBooked` int NOT NULL,
  `TotalPrice` decimal(10,2) NOT NULL,
  `BookingDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `PaymentStatus` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TotalAmount` decimal(10,2) NOT NULL,
  `Status` enum('Paid','Pending','Cancelled') NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`BookingID`),
  KEY `UserID` (`UserID`),
  KEY `FlightID` (`FlightID`)
) ;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`BookingID`, `UserID`, `FlightID`, `SeatsBooked`, `TotalPrice`, `BookingDate`, `PaymentStatus`, `TotalAmount`, `Status`) VALUES
(1, 2, 1, 2, 3000000.00, '2024-12-14 13:17:21', 'Pending', 0.00, 'Pending'),
(2, 3, 2, 1, 1200000.00, '2024-12-14 13:17:21', 'Paid', 0.00, 'Pending');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `flights`
--

CREATE TABLE IF NOT EXISTS `flights` (
  `FlightID` int NOT NULL AUTO_INCREMENT,
  `Departure` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Destination` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `DepartureDate` datetime NOT NULL,
  `ArrivalDate` datetime NOT NULL,
  `TotalSeats` int NOT NULL,
  `AvailableSeats` int NOT NULL,
  `PricePerSeat` decimal(10,2) NOT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CreatedBy` int NOT NULL,
  `Image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`FlightID`),
  KEY `CreatedBy` (`CreatedBy`)
) ;

--
-- Đang đổ dữ liệu cho bảng `flights`
--

INSERT INTO `flights` (`FlightID`, `Departure`, `Destination`, `DepartureDate`, `ArrivalDate`, `TotalSeats`, `AvailableSeats`, `PricePerSeat`, `Status`, `CreatedBy`, `Image`) VALUES
(1, 'Hà Nội', 'Hồ Chí Minh', '2024-12-20 08:00:00', '2024-12-20 10:00:00', 200, 200, 1500000.00, 'Available', 1, 'IMG_9678.JPG'),
(2, 'Hà Nội', 'Đà Nẵng', '2024-12-21 14:00:00', '2024-12-21 15:30:00', 150, 150, 1200000.00, 'Available', 1, 'IMG_9678.JPG');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--


CREATE TABLE IF NOT EXISTS `payments` (
  `PaymentID` int NOT NULL AUTO_INCREMENT,
  `BookingID` int NOT NULL,
  `PaymentMethod` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PaidAmount` decimal(10,2) NOT NULL,
  `PaymentDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`PaymentID`),
  KEY `BookingID` (`BookingID`)
) ;

--
-- Đang đổ dữ liệu cho bảng `payments`
--

INSERT INTO `payments` (`PaymentID`, `BookingID`, `PaymentMethod`, `PaidAmount`, `PaymentDate`) VALUES
(1, 1, 'CreditCard', 3000000.00, '2024-12-14 13:17:21'),
(2, 2, 'BankTransfer', 1200000.00, '2024-12-14 13:17:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotions`
--


CREATE TABLE IF NOT EXISTS `promotions` (
  `PromoID` int NOT NULL AUTO_INCREMENT,
  `PromoCode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Discount` decimal(5,2) NOT NULL,
  `StartDate` datetime NOT NULL,
  `EndDate` datetime NOT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`PromoID`),
  UNIQUE KEY `PromoCode` (`PromoCode`)
) ;

ALTER TABLE promotions ADD COLUMN Description TEXT;
--
-- Đang đổ dữ liệu cho bảng `promotions`
--

INSERT INTO `promotions` (`PromoID`, `PromoCode`, `Discount`, `StartDate`, `EndDate`, `Status`) VALUES
(1, 'NEWYEAR2024', 15.00, '2024-12-01 00:00:00', '2024-12-31 00:00:00', 'Active'),
(2, 'SUMMER2024', 10.00, '2024-06-01 00:00:00', '2024-08-31 00:00:00', 'Active');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--


CREATE TABLE IF NOT EXISTS `users` (
  `UserID` int NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `FullName` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL,
  `Role` enum('User','Admin') NOT NULL DEFAULT 'User',
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `FullName`, `Email`, `PhoneNumber`, `Role`) VALUES
(4, 'User', '$2y$10$3L9Wo3qNRTBApUtrXGHURuLPWYD0YqGNQwZk6zjmlzVPZltXXnaL6', 'User', 'User@email.com', '0123456789', 'User'),
(3, 'Admin0', '$2y$10$JamlkYSJ6Za4yYCIph5Lw.nYZSsM8JMJhYwlsJH9ePdGk.s8GNxfi', 'Admin0', 'Admin@mail.com', '0123456789', 'Admin');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
