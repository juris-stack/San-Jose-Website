-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2019 at 06:45 AM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sjm-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `ID` int(11) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('draft','published','trash') NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`ID`, `slug`, `name`, `description`, `status`, `date_added`) VALUES
(1, 'honda', 'Honda', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam', 'published', '2019-02-06 16:38:42'),
(2, 'yamaha', 'Yamaha', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam', 'published', '2019-02-06 16:43:45'),
(3, 'suzuki', 'Suzuki', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam', 'published', '2019-02-06 16:44:44'),
(5, 'rusi', 'Rusi', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam', 'published', '2019-02-06 16:46:08'),
(7, 'harley-davidson', 'Harley-Davidson', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam', 'published', '2019-02-06 16:47:24'),
(19, 'kawasaki', 'Kawasaki', '', 'published', '2019-02-12 21:14:14'),
(20, 'loncin', 'Loncin', '', 'published', '2019-02-12 21:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `ID` bigint(20) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('draft','published','trash') NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`ID`, `slug`, `name`, `description`, `status`, `date_added`) VALUES
(1, 'oils-and-fluids', 'Oils & Fluids', '', 'published', '2019-02-07 22:42:21'),
(2, 'motor-parts', 'Motor Parts', '', 'published', '2019-02-08 18:15:36'),
(3, 'accessories', 'Accessories', '', 'published', '2019-02-12 13:32:49'),
(4, 'batteries', 'Batteries', '', 'published', '2019-02-12 13:33:06'),
(5, 'tools', 'Tools', '', 'published', '2019-02-12 13:33:51'),
(6, 'others', 'Others', 'Other products', 'published', '2019-02-12 13:40:13');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `ID` bigint(20) NOT NULL,
  `fromID` bigint(20) NOT NULL,
  `from_email` bigint(20) NOT NULL,
  `message` text NOT NULL,
  `read_status` enum('no','yes') DEFAULT 'no',
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `ID` bigint(20) NOT NULL,
  `type` varchar(255) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `status` enum('unread','read') NOT NULL DEFAULT 'unread',
  `rel_ID` bigint(20) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`ID`, `type`, `message`, `status`, `rel_ID`, `date_added`) VALUES
(1, 'order', '', 'read', 10009, '2019-02-19 16:06:39'),
(2, 'order', '', 'read', 10010, '2019-02-19 16:11:12'),
(3, 'order', '', 'read', 10011, '2019-02-22 01:53:20'),
(4, 'order', '', 'read', 10013, '2019-02-26 10:43:55'),
(5, 'order', '', 'read', 10014, '2019-02-26 10:56:45'),
(6, 'order', '', 'read', 10018, '2019-02-26 14:31:59'),
(7, 'order', '', 'read', 10020, '2019-02-26 15:00:02'),
(8, 'order', '', 'read', 10021, '2019-02-26 15:36:42'),
(9, 'order', '', 'read', 0, '2019-02-26 15:37:16'),
(10, 'order', '', 'read', 10022, '2019-02-26 15:39:00'),
(11, 'order', '', 'read', 0, '2019-02-26 15:39:25'),
(12, 'order', '', 'read', 0, '2019-02-26 15:44:05'),
(13, 'order', '', 'read', 10023, '2019-02-26 15:44:21'),
(14, 'order', '', 'read', 0, '2019-02-26 15:44:58'),
(15, 'order', '', 'read', 0, '2019-02-26 15:49:16'),
(16, 'order', '$mssg', 'read', 0, '2019-02-26 15:50:50'),
(17, 'order', 'Cancelled order', 'read', 0, '2019-02-26 15:52:41'),
(18, 'order', 'Cancelled order', 'read', 0, '2019-02-26 15:53:51'),
(19, 'order', 'Cancelled order', 'read', 0, '2019-02-26 15:54:13'),
(20, 'order', 'Cancelled order', 'read', 0, '2019-02-26 15:55:57'),
(21, 'order', 'Cancelled order', 'read', 0, '2019-02-26 15:56:48'),
(22, 'order', 'New order recieved', 'read', 10024, '2019-02-26 15:59:35'),
(23, 'order', 'Cancelled order', 'read', 0, '2019-02-26 15:59:43'),
(24, 'order', 'Cancelled order', 'read', 0, '2019-02-26 16:00:25'),
(25, 'order', 'Cancelled order', 'read', 0, '2019-02-26 16:01:10'),
(26, 'order', 'Cancelled order', 'read', 0, '2019-02-26 16:01:39'),
(27, 'order', 'Cancelled order', 'read', 0, '2019-02-26 16:02:01'),
(28, 'order', 'New order recieved', 'read', 10025, '2019-02-26 16:02:50'),
(29, 'order', 'Cancelled order', 'read', 0, '2019-02-26 16:03:15'),
(30, 'order', 'Cancelled order', 'read', 0, '2019-02-26 16:04:19'),
(31, 'order', 'Cancelled order', 'read', 0, '2019-02-26 16:04:47'),
(32, 'order', 'New order recieved', 'read', 10026, '2019-02-26 16:06:49'),
(33, 'order', 'Cancelled order', 'read', 0, '2019-02-26 16:07:17'),
(34, 'order', 'Cancelled order', 'read', 0, '2019-02-26 16:10:40'),
(35, 'order', 'Cancelled order', 'read', 0, '2019-02-26 16:11:01'),
(36, 'order', 'Cancelled order', 'read', 0, '2019-02-26 16:11:42'),
(37, 'order', 'New order recieved', 'read', 10027, '2019-02-26 16:13:18'),
(38, 'order', 'Cancelled order', 'read', 0, '2019-02-26 16:13:58'),
(39, 'order', 'New order recieved', 'read', 10028, '2019-02-26 16:15:19'),
(40, 'order', 'Cancelled order', 'read', 0, '2019-02-26 16:15:45'),
(41, 'order', '', 'read', 10029, '2019-02-26 16:58:17'),
(42, 'order', '', 'read', 0, '2019-02-26 16:58:39'),
(43, 'order', '', 'read', 0, '2019-02-26 17:03:29'),
(44, 'order', '', 'read', 10029, '2019-02-26 17:06:18'),
(45, 'order', 'Cancelled order', 'read', 10029, '2019-02-26 17:07:36'),
(46, 'order', 'New order recieved', 'read', 10030, '2019-02-26 17:50:21'),
(47, 'order', 'Cancelled order', 'read', 10032, '2019-02-26 18:08:41'),
(48, 'order', 'Cancelled orderasdasd', 'read', 10031, '2019-02-26 18:09:53'),
(49, 'order', 'New order recieved', 'read', 10033, '2019-02-26 18:12:09'),
(50, 'order', 'Cancelled order', 'read', 10033, '2019-02-26 18:12:31'),
(51, 'order', 'New order recieved', 'read', 10036, '2019-02-26 18:47:02'),
(52, 'order', 'New order recieved', 'read', 10037, '2019-02-26 18:48:44'),
(53, 'order', 'New order recieved', 'read', 10039, '2019-02-26 19:29:36'),
(54, 'order', 'New order recieved', 'read', 10040, '2019-02-26 21:03:14'),
(55, 'order', 'New order recieved', 'read', 10050, '2019-02-28 22:33:47'),
(56, 'order', 'New order recieved', 'read', 10052, '2019-03-11 22:26:53'),
(57, 'order', 'New order recieved', 'read', 10054, '2019-03-23 01:01:38'),
(58, 'order', 'New order recieved', 'read', 10078, '2019-03-23 15:39:27'),
(59, 'order', 'New order recieved', 'read', 10082, '2019-03-24 15:20:40'),
(60, 'order', 'New order recieved', 'read', 10084, '2019-03-25 02:33:51');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `ID` int(11) NOT NULL,
  `option_key` varchar(255) NOT NULL,
  `option_value` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`ID`, `option_key`, `option_value`) VALUES
(1, 'site-name', 'San Jose Motors'),
(2, 'site-url', 'http://localhost/SanJose'),
(3, 'company-email', 'iahnnlusica@gmail.com'),
(4, 'company-phone', '09175654543'),
(5, 'recipient-phone', '09174416431'),
(6, 'recipient-name', 'Bryan Lusica'),
(7, 'shipping-fee', '350');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `ID` bigint(20) NOT NULL,
  `type` enum('Online order','Walk-in') NOT NULL,
  `amount` varchar(255) NOT NULL,
  `products` varchar(255) NOT NULL,
  `status` enum('pending','processed','completed','cancelled') NOT NULL,
  `managed_by` varchar(255) NOT NULL,
  `user_details` varchar(1000) NOT NULL,
  `user_ID` bigint(20) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`ID`, `type`, `amount`, `products`, `status`, `managed_by`, `user_details`, `user_ID`, `date_added`) VALUES
(10010, 'Online order', '790', 'a:2:{i:5;i:1;i:1;i:1;}', 'completed', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Bryan\";s:8:\"lastname\";s:6:\"Lusica\";s:5:\"email\";s:23:\"admin@sanjosemotors.com\";s:9:\"telephone\";s:11:\"09174416431\";s:7:\"address\";s:6:\"Canawa\";s:4:\"city\";s:8:\"Candijay\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6312\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Bryan\";s:8:\"lastname\";s:6:\"Lusica\";s:9:\"telephone\";s:11:\"09174416431\";s:7:\"address\";s:6:\"Canawa\";s:4:\"city\";s:8:\"Candijay\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6312\";}}', 2, '2019-02-19 16:11:11'),
(10009, 'Online order', '2175.8', 'a:2:{i:10;i:1;i:8;i:2;}', 'pending', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Bryan\";s:8:\"lastname\";s:6:\"Lusica\";s:5:\"email\";s:23:\"admin@sanjosemotors.com\";s:9:\"telephone\";s:11:\"09174416431\";s:7:\"address\";s:6:\"Canawa\";s:4:\"city\";s:8:\"Candijay\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6312\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Bryan\";s:8:\"lastname\";s:6:\"Lusica\";s:9:\"telephone\";s:11:\"09174416431\";s:7:\"address\";s:6:\"Canawa\";s:4:\"city\";s:8:\"Candijay\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6312\";}}', 0, '2019-02-19 16:06:38'),
(10011, 'Online order', '120', 'a:1:{i:7;i:1;}', 'cancelled', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:11:\"09079966291\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"3200\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:11:\"09079966291\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"3200\";}}', 0, '2019-02-22 01:53:19'),
(10012, 'Online order', '2400', 'a:1:{i:9;s:1:\"3\";}', 'completed', '', '', 0, '2019-02-26 10:07:38'),
(10013, 'Online order', '240', 'a:1:{i:5;i:1;}', 'completed', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 7, '2019-02-26 10:43:54'),
(10014, 'Online order', '900', 'a:1:{i:1;i:1;}', 'completed', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:11:\"09098109891\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 2, '2019-02-26 10:56:44'),
(10015, 'Online order', '120', 'a:1:{i:6;s:1:\"1\";}', 'completed', '', '', 0, '2019-02-26 11:16:12'),
(10016, 'Online order', '600', 'a:1:{i:1;s:1:\"1\";}', '', '', '', 0, '2019-02-26 11:40:46'),
(10017, 'Online order', '240', 'a:1:{i:7;s:1:\"2\";}', '', '', '', 0, '2019-02-26 12:47:26'),
(10018, 'Online order', '2115.8', 'a:3:{i:7;i:2;i:10;i:1;i:8;i:1;}', 'cancelled', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:11:\"09287600666\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:11:\"09287600000\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 8, '2019-02-26 14:31:58'),
(10019, 'Online order', '1275.8', 'a:3:{i:7;s:1:\"1\";i:4;s:1:\"1\";i:10;s:1:\"1\";}', '', '', '', 0, '2019-02-26 14:41:06'),
(10020, 'Online order', '530', 'a:1:{i:4;i:1;}', 'cancelled', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:7:\"Dorothy\";s:8:\"lastname\";s:6:\"Tambis\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:11:\"09182309182\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 8, '2019-02-26 15:00:01'),
(10021, 'Online order', '1080', 'a:2:{i:4;i:1;i:1;i:1;}', 'cancelled', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 8, '2019-02-26 15:36:41'),
(10022, 'Online order', '590', 'a:1:{i:5;i:1;}', 'cancelled', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 8, '2019-02-26 15:38:59'),
(10023, 'Online order', '590', 'a:1:{i:5;i:1;}', 'cancelled', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 8, '2019-02-26 15:44:20'),
(10024, 'Online order', '530', 'a:1:{i:4;i:1;}', 'cancelled', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 8, '2019-02-26 15:59:34'),
(10025, 'Online order', '530', 'a:1:{i:4;i:1;}', 'cancelled', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 8, '2019-02-26 16:02:49'),
(10026, 'Online order', '530', 'a:1:{i:4;i:1;}', 'cancelled', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 8, '2019-02-26 16:06:48'),
(10027, 'Online order', '900', 'a:1:{i:1;i:1;}', 'cancelled', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 8, '2019-02-26 16:13:17'),
(10028, 'Online order', '530', 'a:1:{i:4;i:1;}', 'cancelled', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 8, '2019-02-26 16:15:18'),
(10040, 'Online order', '900', 'a:1:{i:8;i:1;}', 'completed', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 8, '2019-02-26 21:03:13'),
(10030, 'Online order', '530', 'a:1:{i:4;i:1;}', 'pending', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 0, '2019-02-26 17:50:20'),
(10031, 'Online order', '530', 'a:1:{i:4;i:1;}', 'cancelled', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 8, '2019-02-26 18:06:45'),
(10032, 'Online order', '530', 'a:0:{}', 'cancelled', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 8, '2019-02-26 18:07:30'),
(10033, 'Online order', '530', 'a:1:{i:4;i:1;}', 'cancelled', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 8, '2019-02-26 18:12:08'),
(10034, 'Walk-in', '1200', 'a:1:{i:1;s:1:\"2\";}', 'completed', '', '', 0, '2019-02-26 18:22:24'),
(10035, 'Walk-in', '975.8', 'a:1:{i:10;s:1:\"1\";}', 'completed', '', '', 0, '2019-02-26 18:38:04'),
(10041, 'Walk-in', '0', 'a:1:{s:0:\"\";N;}', 'completed', '', '', 0, '2019-02-26 21:27:36'),
(10047, 'Walk-in', '800', 'a:1:{i:9;s:1:\"1\";}', 'completed', '', '', 0, '2019-02-27 14:23:46'),
(10043, 'Walk-in', '0', 'a:1:{s:0:\"\";N;}', 'completed', '', '', 0, '2019-02-26 21:32:02'),
(10044, 'Walk-in', '975.8', 'a:1:{i:10;s:1:\"1\";}', 'completed', '', '', 0, '2019-02-26 21:37:13'),
(10045, 'Walk-in', '0', 'a:2:{i:3;s:1:\"1\";i:9;s:1:\"1\";}', 'completed', '', '', 0, '2019-02-26 21:38:20'),
(10046, 'Walk-in', '975.8', 'a:1:{i:10;s:1:\"1\";}', 'completed', '', '', 0, '2019-02-27 10:04:33'),
(10048, 'Walk-in', '150', 'a:1:{i:3;s:1:\"1\";}', 'completed', '', '', 0, '2019-02-27 15:58:38'),
(10049, 'Walk-in', '600', 'a:1:{i:1;s:1:\"1\";}', 'completed', '', '', 0, '2019-02-28 22:33:13'),
(10050, 'Online order', '1940', 'a:3:{i:9;i:1;i:6;i:2;i:8;i:1;}', 'processed', 'admin', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 0, '2019-03-25 09:43:20'),
(10038, 'Walk-in', '975.8', 'a:1:{i:10;s:1:\"1\";}', 'completed', '', '', 0, '2019-02-26 18:55:43'),
(10039, 'Online order', '590', 'a:1:{i:5;i:1;}', 'completed', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:15:\"juris@gmail.com\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:10:\"0928768432\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 2, '2019-02-26 19:29:35'),
(10051, 'Walk-in', '180', 'a:1:{i:4;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-04 15:15:31'),
(10052, 'Online order', '900', 'a:1:{i:1;i:1;}', 'processed', 'admin', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:22:\"jurisdaguplo@gmail.com\";s:9:\"telephone\";s:11:\"09810928390\";s:7:\"address\";s:4:\"Booy\";s:4:\"city\";s:10:\"Tagbilaran\";s:5:\"state\";s:4:\"City\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Bidot\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:11:\"09891820381\";s:7:\"address\";s:4:\"Booy\";s:4:\"city\";s:10:\"Tagbilaran\";s:5:\"state\";s:4:\"City\";s:3:\"zip\";s:4:\"6300\";}}', 9, '2019-03-25 03:40:09'),
(10053, 'Walk-in', '240', 'a:1:{i:5;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-22 19:04:59'),
(10054, 'Online order', '470', 'a:1:{i:7;i:1;}', 'processed', 'Staff', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:16:\"jurisd@gmail.com\";s:9:\"telephone\";s:10:\"9809810236\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:10:\"Tagbilaran\";s:5:\"state\";s:4:\"City\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Bidot\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:11:\"09880981209\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:10:\"Tagbilaran\";s:5:\"state\";s:4:\"City\";s:3:\"zip\";s:4:\"6300\";}}', 0, '2019-03-25 03:38:28'),
(10055, 'Walk-in', '600', 'a:1:{i:1;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 12:58:50'),
(10056, 'Walk-in', '600', 'a:1:{i:1;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 12:59:43'),
(10057, 'Walk-in', '600', 'a:1:{i:1;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 13:01:21'),
(10058, 'Walk-in', '600', 'a:1:{i:1;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 13:02:54'),
(10059, 'Walk-in', '600', 'a:1:{i:1;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 13:03:29'),
(10060, 'Walk-in', '600', 'a:1:{i:1;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 13:13:44'),
(10061, 'Walk-in', '600', 'a:1:{i:1;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 13:18:33'),
(10062, 'Walk-in', '600', 'a:1:{i:1;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 13:22:07'),
(10063, 'Walk-in', '600', 'a:1:{i:1;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 13:23:17'),
(10064, 'Walk-in', '600', 'a:1:{i:1;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 13:25:17'),
(10065, 'Walk-in', '600', 'a:1:{i:1;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 13:28:12'),
(10066, 'Walk-in', '600', 'a:1:{i:1;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 13:34:36'),
(10067, 'Walk-in', '810', 'a:2:{i:6;s:1:\"3\";i:3;s:1:\"3\";}', 'completed', '', '', 0, '2019-03-23 13:43:05'),
(10068, 'Walk-in', '1200', 'a:1:{i:5;s:1:\"5\";}', 'completed', '', '', 0, '2019-03-23 13:43:46'),
(10069, 'Walk-in', '1200', 'a:1:{i:8;s:1:\"2\";}', 'completed', '', '', 0, '2019-03-23 13:46:26'),
(10070, 'Walk-in', '360', 'a:1:{i:4;s:1:\"2\";}', 'completed', '', '', 0, '2019-03-23 13:58:08'),
(10071, 'Walk-in', '480', 'a:1:{i:7;s:1:\"4\";}', 'completed', '', '', 0, '2019-03-23 13:58:24'),
(10072, 'Walk-in', '12', 'a:1:{i:15;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 14:04:07'),
(10073, 'Walk-in', '975.8', 'a:1:{i:10;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 14:04:59'),
(10074, 'Walk-in', '800', 'a:1:{i:9;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 14:05:58'),
(10075, 'Walk-in', '800', 'a:1:{i:9;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 14:12:33'),
(10076, 'Walk-in', '1600', 'a:1:{i:9;s:1:\"2\";}', 'completed', '', '', 0, '2019-03-23 14:15:16'),
(10077, 'Walk-in', '180', 'a:1:{i:4;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 14:16:41'),
(10078, 'Online order', '1325.8', 'a:1:{i:10;i:1;}', 'processed', 'Staff', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:6:\"Azores\";s:8:\"lastname\";s:7:\"Daguplo\";s:5:\"email\";s:23:\"admin@sanjosemotors.com\";s:9:\"telephone\";s:10:\"9810923051\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Bidot\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:11:\"01928301280\";s:7:\"address\";s:15:\"Sierra Bullones\";s:4:\"city\";s:15:\"Sierra Bullones\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 2, '2019-03-23 15:39:26'),
(10079, 'Walk-in', '180', 'a:1:{i:4;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 20:54:15'),
(10080, 'Walk-in', '975.8', 'a:1:{i:10;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-23 21:09:57'),
(10081, 'Walk-in', '180', 'a:1:{i:4;s:1:\"1\";}', 'completed', '', '', 0, '2019-03-24 13:33:22'),
(10082, 'Online order', '1325.8', 'a:1:{i:10;i:1;}', 'processed', '', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:7:\"Giannis\";s:8:\"lastname\";s:13:\"Antetokounmpo\";s:5:\"email\";s:17:\"giannis@gmail.com\";s:9:\"telephone\";s:11:\"09182309764\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Juris\";s:8:\"lastname\";s:11:\"Oakenshield\";s:9:\"telephone\";s:11:\"01983909128\";s:7:\"address\";s:9:\"Poblacion\";s:4:\"city\";s:15:\"Sierra Bullones\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 11, '2019-03-24 15:20:39'),
(10083, 'Walk-in', '975.8', 'a:1:{i:10;s:1:\"1\";}', 'completed', 'admin', '', 0, '2019-03-25 02:25:22'),
(10084, 'Online order', '1325.8', 'a:1:{i:10;i:1;}', 'processed', 'admin', 'a:2:{s:7:\"billing\";a:8:{s:9:\"firstname\";s:7:\"Giannis\";s:8:\"lastname\";s:13:\"Antetokounmpo\";s:5:\"email\";s:17:\"giannis@gmail.com\";s:9:\"telephone\";s:11:\"09810298309\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}s:8:\"shipping\";a:7:{s:9:\"firstname\";s:5:\"Bidot\";s:8:\"lastname\";s:7:\"Daguplo\";s:9:\"telephone\";s:11:\"09182309812\";s:7:\"address\";s:12:\"Purok 5 Booy\";s:4:\"city\";s:15:\"Tagbilaran City\";s:5:\"state\";s:5:\"Bohol\";s:3:\"zip\";s:4:\"6300\";}}', 11, '2019-03-25 02:33:50'),
(10085, 'Walk-in', '975.8', 'a:1:{i:10;s:1:\"1\";}', 'completed', 'Staff', '', 0, '2019-03-25 02:49:22'),
(10086, 'Walk-in', '975.8', 'a:1:{i:10;s:1:\"1\";}', 'completed', 'Staff', '', 0, '2019-03-25 03:05:44');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ID` bigint(20) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `excerpt` text NOT NULL,
  `description` text NOT NULL,
  `category` bigint(20) NOT NULL,
  `brand` bigint(20) NOT NULL,
  `stocks` int(11) NOT NULL,
  `price` varchar(255) NOT NULL,
  `sale_price` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `status` enum('draft','published','trash') NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ID`, `slug`, `name`, `excerpt`, `description`, `category`, `brand`, `stocks`, `price`, `sale_price`, `sku`, `status`, `date_added`) VALUES
(1, 'chain-and-sprocket-set', 'Chain & Sprocket Set', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 2, 0, 0, '600', '550', '', 'published', '2019-02-06 22:51:16'),
(3, 'xrm-125-throttle-cable', 'XRM 125 Throttle Cable', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 2, 1, 0, '150', '', '', 'published', '2019-02-12 22:49:29'),
(4, 'side-mirror-plastic', 'Side Mirror Plastic', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 3, 0, 0, '180', '', '', 'published', '2019-02-12 22:50:54'),
(5, 'side-mirror-alloy', 'Side Mirror Alloy', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 3, 0, 0, '240', '', '', 'published', '2019-02-12 22:51:45'),
(6, 'wd40', 'WD40', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 1, 0, 0, '120', '', '', 'published', '2019-02-12 22:59:33'),
(7, 'vs1', 'VS1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 1, 0, 0, '120', '', '', 'published', '2019-02-12 23:01:58'),
(8, 'battery-for-harley-davidson', 'Battery  for Harley Davidson', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 4, 7, 0, '600', '550', '', 'published', '2019-02-12 23:06:37'),
(9, 'xrm-shock-absorber', 'XRM Shock Absorber', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 2, 1, 500, '800', '', '', 'published', '2019-02-12 23:07:23'),
(10, 'xrm-headlight', 'XRM Headlight', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 2, 1, 0, '975.80', '', '', 'published', '2019-02-12 23:09:58');

-- --------------------------------------------------------

--
-- Table structure for table `support`
--

CREATE TABLE `support` (
  `ID` bigint(20) NOT NULL,
  `session` varchar(255) NOT NULL,
  `from_name` varchar(255) NOT NULL,
  `from_email` varchar(255) NOT NULL,
  `status` enum('open','replied','closed') NOT NULL DEFAULT 'open',
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `support`
--

INSERT INTO `support` (`ID`, `session`, `from_name`, `from_email`, `status`, `date_added`) VALUES
(18, 'iahnn-1550572062', 'iahnn', 'iahnn@yahoo.com', 'replied', '2019-02-19 18:27:43'),
(17, 'iahnn-1550500542', 'iahnn', 'iahnn@yahoo.com', 'closed', '2019-02-18 22:35:42'),
(15, 'bryan-lusica-1550217746', 'Bryan Lusica', 'iahnn@yahoo.com', 'closed', '2019-02-15 16:02:26'),
(16, 'bryan-lusica-1550222795', 'Bryan Lusica', 'iahnn@yahoo.com', 'replied', '2019-02-15 17:26:35'),
(19, 'emy-fe-1550649955', 'Emy Fe', 'emyfe@gmail.com', 'closed', '2019-02-20 17:05:55'),
(25, 'juris-1551684024', 'juris', 'juris@gmail.com', 'closed', '2019-03-04 15:20:24'),
(26, 'giannis-1553273831', 'Giannis', 'giannis@gmail.com', 'closed', '2019-03-23 00:57:11'),
(24, 'asd-1551248663', 'asd', 'asd@gmail.com', 'closed', '2019-02-27 14:24:24'),
(27, 'giannis-1553478878', 'giannis', 'giannis@gmail.com', 'closed', '2019-03-25 09:54:38'),
(37, 'wassup-1553480666', 'wassup', 'wassup@gmail.com', 'closed', '2019-03-25 10:24:26'),
(38, 'we-1553480817', 'we', 'we@gmail.com', 'closed', '2019-03-25 10:26:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` bigint(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` enum('0','1') NOT NULL,
  `status` enum('online','offline','isle') NOT NULL DEFAULT 'offline',
  `role` int(11) NOT NULL,
  `auth` varchar(255) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `username`, `email`, `password`, `active`, `status`, `role`, `auth`, `reg_date`) VALUES
(8, 'Dorothy', 'dean@gmail.com', 'aabf11b3599bbdf37f8f6020e467fd2d', '0', 'online', 1, '', '2019-02-26 14:20:21'),
(2, 'admin', 'admin@sanjosemotors.com', '21232f297a57a5a743894a0e4a801fc3', '1', 'offline', 3, '', '2019-02-08 23:19:41'),
(9, 'Juris58', 'jurisdaguplo@gmail.com', '609b21c46ce6011319357f8a21bd980d', '1', 'offline', 1, '', '2019-03-11 22:25:09'),
(6, 'Staff', 'staff@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '1', 'offline', 2, '', '2019-02-26 10:05:41'),
(7, 'Bidot', 'Bidot@gmail.com', 'd2c965752c64f34039f157691be251c9', '1', 'offline', 1, '', '2019-02-26 10:42:50'),
(10, 'Kenneth', 'kenneth@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '1', 'offline', 2, '', '2019-03-23 21:08:10'),
(11, 'giannis', 'giannis@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '1', 'offline', 1, '', '2019-03-24 15:04:33');

-- --------------------------------------------------------

--
-- Table structure for table `user_meta`
--

CREATE TABLE `user_meta` (
  `ID` bigint(20) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` varchar(255) NOT NULL,
  `user_ID` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_meta`
--

INSERT INTO `user_meta` (`ID`, `meta_key`, `meta_value`, `user_ID`) VALUES
(69, 'billing-telephone', '1234', 2),
(68, 'billing-lastname', '', 2),
(66, 'shipping-telephone', '09174416431', 1),
(65, 'billing-telephone', '09174416431', 1),
(64, 'cart-items', 'a:0:{}', 2),
(67, 'billing-firstname', 'San Jose Motorparts', 2),
(63, 'cart-items', 'a:0:{}', 0),
(81, 'cart-items', 'a:0:{}', 7),
(62, 'cart-items', 'a:0:{}', 1),
(60, 'shipping-zip', '6312', 1),
(58, 'shipping-city', 'Candijay', 1),
(59, 'shipping-state', 'Bohol', 1),
(57, 'shipping-address', 'Canawa', 1),
(56, 'shipping-lastname', 'Lusica', 1),
(55, 'shipping-firstname', 'Bryan', 1),
(54, 'billing-zip', '6312', 1),
(53, 'billing-state', 'Bohol', 1),
(52, 'billing-city', 'Candijay', 1),
(61, 'email', 'emyfecena@gmail.com', 1),
(51, 'billing-address', 'Canawa', 1),
(50, 'billing-lastname', 'Lusica', 1),
(49, 'billing-firstname', 'Bryan', 1),
(70, 'billing-address', '', 2),
(71, 'billing-city', '', 2),
(72, 'billing-state', '', 2),
(73, 'billing-zip', '', 2),
(74, 'shipping-firstname', '', 2),
(75, 'shipping-lastname', '', 2),
(76, 'shipping-telephone', '', 2),
(77, 'shipping-address', '', 2),
(78, 'shipping-city', '', 2),
(79, 'shipping-state', '', 2),
(80, 'shipping-zip', '', 2),
(83, 'cart-items', 'a:0:{}', 9),
(82, 'cart-items', 'a:0:{}', 8),
(84, 'cart-items', 'a:1:{i:9;i:1;}', 11),
(85, 'billing-firstname', 'Giannis', 11),
(86, 'billing-lastname', 'Antetokounmpo', 11),
(87, 'billing-telephone', '09810298309', 11),
(88, 'billing-address', 'Purok 5 Booy', 11),
(89, 'billing-city', 'Tagbilaran City', 11),
(90, 'billing-state', 'Bohol', 11),
(91, 'billing-zip', '6300', 11),
(92, 'shipping-firstname', '', 11),
(93, 'shipping-lastname', '', 11),
(94, 'shipping-telephone', '09182309812', 11),
(95, 'shipping-address', '', 11),
(96, 'shipping-city', '', 11),
(97, 'shipping-state', '', 11),
(98, 'shipping-zip', '', 11);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `support`
--
ALTER TABLE `support`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_meta`
--
ALTER TABLE `user_meta`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10087;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `support`
--
ALTER TABLE `support`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_meta`
--
ALTER TABLE `user_meta`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
