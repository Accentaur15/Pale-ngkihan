-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2023 at 02:11 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pale-ngkihan_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_accounts`
--

CREATE TABLE `admin_accounts` (
  `id` int(50) NOT NULL,
  `unique_id` text NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `profile_picture` text NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `role` tinyint(1) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_accounts`
--

INSERT INTO `admin_accounts` (`id`, `unique_id`, `first_name`, `last_name`, `username`, `password`, `profile_picture`, `last_login`, `role`, `date_added`, `date_updated`) VALUES
(23, 'A-2023-07-05-1', 'Lelouch', 'Britannia', 'accentaur15', '7cef8a734855777c2a9d0caf42666e69', 'admin_profiles/A-2023-07-05-1/profilepicture.png', NULL, 1, '2023-07-05 07:48:26', NULL),
(24, 'A-2023-07-05-2', 'Kristine', 'Lim', 'tintinas', '7cef8a734855777c2a9d0caf42666e69', 'admin_profiles/A-2023-07-05-2/profilepicture.png', NULL, 2, '2023-07-05 07:49:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `buyer_accounts`
--

CREATE TABLE `buyer_accounts` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(100) NOT NULL,
  `first_name` text NOT NULL,
  `middle_name` text NOT NULL,
  `last_name` text NOT NULL,
  `gender` text NOT NULL,
  `contact` text NOT NULL,
  `address` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `online_status` tinyint(4) DEFAULT 1,
  `profile_picture` text NOT NULL,
  `valid_id` text NOT NULL,
  `delete_flag` tinyint(1) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buyer_accounts`
--

INSERT INTO `buyer_accounts` (`id`, `unique_id`, `first_name`, `middle_name`, `last_name`, `gender`, `contact`, `address`, `email`, `password`, `status`, `online_status`, `profile_picture`, `valid_id`, `delete_flag`, `date_created`, `date_updated`) VALUES
(41, 'B-2023-06-28-1', 'Accentaur', 'Vi', 'Brittania', 'Male', '0997488681', 'Pampanga', 'dkcglenn@gmail.com', '48e8b1e1f2b13da43073d551636f4b1a', 1, 0, 'buyer_profiles/B-2023-06-28-1/profilePicture.png', 'buyer_profiles/B-2023-06-28-1/validid.png', 0, '2023-06-28 05:10:48', '2023-08-21 12:07:19');

-- --------------------------------------------------------

--
-- Table structure for table `buyer_bids`
--

CREATE TABLE `buyer_bids` (
  `id` int(30) NOT NULL,
  `harvest_schedule_id` int(30) NOT NULL,
  `buyer_id` int(30) NOT NULL,
  `bid_amount` double DEFAULT NULL,
  `bid_status` enum('pending','accepted','rejected','canceled') NOT NULL,
  `bid_code` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buyer_bids`
--

INSERT INTO `buyer_bids` (`id`, `harvest_schedule_id`, `buyer_id`, `bid_amount`, `bid_status`, `bid_code`) VALUES
(45, 22, 41, NULL, 'accepted', 'BID-1691594204-OUXNG6JS'),
(48, 21, 41, 53, 'canceled', 'BID-1691594583-VBECNA9B');

-- --------------------------------------------------------

--
-- Table structure for table `cart_list`
--

CREATE TABLE `cart_list` (
  `id` int(30) NOT NULL,
  `buyer_id` int(30) DEFAULT NULL,
  `product_id` int(30) DEFAULT NULL,
  `quantity` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`id`, `name`, `description`, `status`, `date_created`, `date_updated`) VALUES
(1, 'Dinorado', 'Dinorado rice is a premium variety known for its long, slender grains and golden color. It has a delightful aroma, subtle sweetness, and a slightly sticky texture when cooked. This gourmet rice is ideal for special occasions and traditional Filipino dishes. It offers a unique and exceptional rice experience for those seeking superior quality.', 1, '2023-07-04 14:13:25', '2023-07-05 20:32:59'),
(3, 'Malagkit Rice', 'Malagkit rice, also known as sticky rice or glutinous rice, is a popular rice variety commonly consumed in the Philippines. Unlike regular rice, Malagkit rice has a higher starch content, giving it a sticky and chewy texture when cooked. This makes it perfect for traditional Filipino desserts like suman, biko, and kakanin. It is also used to make savory dishes such as sticky rice with mango or as a side dish to complement rich stews and curries. Malagkit rice is a staple in Filipino cuisine, cherished for its unique texture and versatility in creating both sweet and savory dishes.', 1, '2023-07-04 16:45:46', NULL),
(4, 'Jasmine Rice', 'Jasmine rice, also known as fragrant rice, is a type of long-grain rice widely cultivated in Southeast Asia, particularly in Thailand. Renowned for its delicate floral aroma and subtle nutty flavor, Jasmine rice is a favorite among rice connoisseurs. The grains are soft, tender, and slightly sticky when cooked, making it an excellent choice for various Asian dishes, including stir-fries, curries, and rice pilafs. Jasmine rice adds a delightful fragrance to any meal and enhances the overall dining experience. Its versatility, aromatic qualities, and pleasant taste make it a popular choice for both everyday meals and special occasions.', 1, '2023-07-04 16:49:46', '2023-07-04 22:43:56'),
(5, 'Sinandomeng Rice', 'Sinandomeng rice is a popular variety of rice in the Philippines known for its excellent quality and taste. It is characterized by its medium-grain size, slightly sticky texture, and aromatic flavor. Sinandomeng rice is commonly used for everyday meals and is a staple in Filipino households. It cooks up fluffy and is versatile enough to be paired with various dishes, from savory viands to comforting rice-based desserts.', 1, '2023-07-04 16:57:09', NULL),
(6, 'Jasponica Rice', 'Jasponica rice is a type of rice that combines the characteristics of both Japanese and Japonica rice varieties. It is known for its short to medium grain size, sticky texture, and slightly sweet flavor. Jasponica rice is popular in the Philippines due to its versatility and ability to absorb flavors well, making it suitable for a wide range of dishes. It is commonly used in traditional Filipino recipes such as sushi, rice bowls, and rice cakes. Jasponica rice is favored for its stickiness, which makes it easier to eat with chopsticks and ideal for making sushi rolls. It is also a preferred choice for making rice desserts and snacks. With its unique combination of traits, Jasponica rice offers a delightful eating experience and is highly valued by Filipino rice enthusiasts.', 1, '2023-07-04 17:00:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `harvest_schedule`
--

CREATE TABLE `harvest_schedule` (
  `id` int(30) NOT NULL,
  `seller_id` int(30) NOT NULL,
  `date_scheduled` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rice_type` text NOT NULL,
  `harvest_image` text NOT NULL,
  `quantity_available` int(11) DEFAULT NULL,
  `location` text DEFAULT NULL,
  `status` enum('upcoming','ongoing','completed') DEFAULT NULL,
  `bidding_status` tinyint(4) NOT NULL,
  `starting_bid` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `harvest_schedule`
--

INSERT INTO `harvest_schedule` (`id`, `seller_id`, `date_scheduled`, `rice_type`, `harvest_image`, `quantity_available`, `location`, `status`, `bidding_status`, `starting_bid`) VALUES
(21, 10, '2023-08-08 16:00:00', 'Dinoradoto', '../seller_profiles/S-2023-07-06-1/-64d0a6114f698.png', 200, 'Latitude: 15.1521, Longitude: 120.5863', 'upcoming', 1, 0),
(22, 10, '2023-08-10 12:14:10', 'Jasmine Rice', '../seller_profiles/S-2023-07-06-1/-64d0fdeae3bb0.png', 400, 'dito lang sa gilid', 'completed', 0, 23);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` varchar(100) NOT NULL,
  `outgoing_msg_id` varchar(100) NOT NULL,
  `msg` varchar(1000) NOT NULL,
  `time_stamp` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`, `time_stamp`) VALUES
(1, 'S-2023-07-25-2024', 'B-2023-06-28-1', 'hello', '2023-08-12 13:56:46'),
(2, 'S-2023-07-25-2024', 'B-2023-06-28-1', 'maganda poba weather diyan', '2023-08-12 13:56:46'),
(3, 'S-2023-07-06-1', 'B-2023-06-28-1', 'manyaman poba talaga?', '2023-08-12 13:56:46'),
(4, 'S-2023-07-06-1', 'B-2023-06-28-1', 'sarap po niyan', '2023-08-12 13:57:01'),
(5, 'B-2023-06-28-1', 'S-2023-07-06-1', 'oo manyaman nga po ito', '2023-08-13 12:00:03'),
(6, 'S-2023-07-06-1', 'B-2023-06-28-1', 'wehh di nga', '2023-08-13 12:06:13'),
(7, 'B-2023-06-28-1', 'S-2023-07-06-1', 'oo nga po manyaman talaga ito', '2023-08-13 12:06:45'),
(8, 'B-2023-06-28-1', 'S-2023-07-06-1', 'kung dika sigurado bumili ka', '2023-08-13 12:10:56'),
(9, 'B-2023-06-28-1', 'S-2023-07-06-1', 'bili ka', '2023-08-15 11:44:13');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(30) NOT NULL,
  `order_code` varchar(100) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `notification_title` text NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_seen` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `order_code`, `buyer_id`, `notification_title`, `message`, `timestamp`, `is_seen`) VALUES
(15, 'ORD-1691586595-5X5BMQKW', 41, 'Order Placed', 'Your order with Order Number ORD-1691586595-5X5BMQKW has been successfully placed.\n', '2023-08-09 13:09:55', 0),
(17, 'ORD-1690810528-7780DBGN', 41, 'Order Status: Pending', 'The order with code \'ORD-1690810528-7780DBGN\' has been updated to status \'Order Status: Pending\'.', '2023-08-09 14:55:16', 0),
(18, 'ORD-1690810528-7780DBGN', 41, 'Order Status: Confirmed', 'The order with code \'ORD-1690810528-7780DBGN\' has been updated to status \'Order Status: Confirmed\'.', '2023-08-09 14:55:53', 0),
(19, 'BID-1691594204-OUXNG6JS', 41, 'Bid Status: Bid Placed', 'The Bid Order with the code \'BID-1691594204-OUXNG6JS\' has been succesfully been placed', '2023-08-09 15:16:44', 0),
(22, 'BID-1691594583-VBECNA9B', 41, 'Bid Status: Bid Placed', 'The bid order with the code \'BID-1691594583-VBECNA9B\' that has a bid amount of \'₱53\' has been succesfully been placed', '2023-08-09 15:23:03', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `quantity` double NOT NULL DEFAULT 0,
  `price` double NOT NULL DEFAULT 0,
  `review_status` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price`, `review_status`, `date_created`) VALUES
(37, 22, 1, 67, 0, '2023-08-09 13:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `order_list`
--

CREATE TABLE `order_list` (
  `id` int(30) NOT NULL,
  `order_code` varchar(100) NOT NULL,
  `buyer_id` int(30) NOT NULL,
  `seller_id` int(30) NOT NULL,
  `total_amount` double NOT NULL DEFAULT 0,
  `delivery_address` text NOT NULL,
  `order_status` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `payment_method` varchar(100) NOT NULL,
  `online_payment_receipt` text DEFAULT NULL,
  `delivery_method` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_list`
--

INSERT INTO `order_list` (`id`, `order_code`, `buyer_id`, `seller_id`, `total_amount`, `delivery_address`, `order_status`, `date_created`, `date_updated`, `payment_method`, `online_payment_receipt`, `delivery_method`) VALUES
(34, 'ORD-1690810528-7780DBGN', 41, 10, 150, 'Pampanga', 1, '2023-07-31 13:35:28', '2023-08-09 14:55:53', 'Online Payment', NULL, 'Delivery'),
(35, 'ORD-1690812777-KLY4U5ZK', 41, 10, 750, 'Pampanga', 4, '2023-07-31 14:12:57', '2023-08-07 06:34:59', 'Online Payment', NULL, 'Delivery'),
(37, 'ORD-1691586595-5X5BMQKW', 41, 11, 67, 'Pampanga', 1, '2023-08-09 13:09:55', '2023-08-09 13:15:18', 'Cash', NULL, 'Pick Up');

-- --------------------------------------------------------

--
-- Table structure for table `product_list`
--

CREATE TABLE `product_list` (
  `id` int(30) NOT NULL,
  `seller_id` int(30) DEFAULT NULL,
  `category_id` int(30) DEFAULT NULL,
  `product_name` text NOT NULL,
  `product_description` text NOT NULL,
  `price` float NOT NULL,
  `unit` text NOT NULL,
  `quantity` int(11) NOT NULL,
  `product_image` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_list`
--

INSERT INTO `product_list` (`id`, `seller_id`, `category_id`, `product_name`, `product_description`, `price`, `unit`, `quantity`, `product_image`, `status`, `date_created`, `date_updated`) VALUES
(22, 11, 6, 'Jasponica Rice', '<p>Jasponica Rice is the best so buy now</p>', 67, 'kg', 200, 'seller_profiles/S-2023-07-25-2024/Japonica Rice.jpg', 1, '2023-07-25 10:36:44', '2023-07-31 11:04:18');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `reviewer_name` varchar(255) NOT NULL,
  `review_text` text NOT NULL,
  `rating` int(11) NOT NULL,
  `review_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seller_accounts`
--

CREATE TABLE `seller_accounts` (
  `id` int(30) NOT NULL,
  `unique_id` varchar(100) NOT NULL,
  `username` text NOT NULL,
  `shop_name` text NOT NULL,
  `shop_owner` text NOT NULL,
  `gender` text NOT NULL,
  `contact` text NOT NULL,
  `address` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `has_pstore` text NOT NULL,
  `business_permit` text DEFAULT NULL,
  `dti_permit` text DEFAULT NULL,
  `mayors_permit` text DEFAULT NULL,
  `valid_id` text NOT NULL,
  `shop_logo` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `online_status` tinyint(4) DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller_accounts`
--

INSERT INTO `seller_accounts` (`id`, `unique_id`, `username`, `shop_name`, `shop_owner`, `gender`, `contact`, `address`, `email`, `password`, `has_pstore`, `business_permit`, `dti_permit`, `mayors_permit`, `valid_id`, `shop_logo`, `status`, `online_status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(10, 'S-2023-07-06-1', 'ManyamanNasi101', 'ManyamanNasi', 'Manyaman A. Nasi', 'Male', '0997488682', 'Arayat', 'nasiisthelife@gmail.com', '7cef8a734855777c2a9d0caf42666e69', 'Yes', 'seller_profiles/S-2023-07-06-1/businesspermit.png', 'seller_profiles/S-2023-07-06-1/dtipermit.png', 'seller_profiles/S-2023-07-06-1/mayorspermit.png', 'seller_profiles/S-2023-07-06-1/validid.png', 'seller_profiles/S-2023-07-06-1/shoplogo.png', 1, 0, 0, '2023-07-06 12:17:51', '2023-08-21 11:33:17'),
(11, 'S-2023-07-25-2024', 'palaynaman', 'Palayseller', 'palaytheseller', 'Male', '09978726342', 'Batasan', 'palaytheseller@seller.com', '7cef8a734855777c2a9d0caf42666e69', 'No', '', '', '', 'seller_profiles/S-2023-07-25-2024/validid.png', 'seller_profiles/S-2023-07-25-2024/shoplogo.png', 1, 0, 0, '2023-07-25 10:31:51', '2023-08-18 12:32:29');

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `ticket_id` int(11) NOT NULL,
  `user_uniqueid` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `urgency_level` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `subject` text NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`ticket_id`, `user_uniqueid`, `category`, `urgency_level`, `status`, `subject`, `description`, `created_at`, `updated_at`) VALUES
(12, 'S-2023-07-06-1', 'General Inquiry', 'Low', 'in progress', 'Bug Report', 'tcgfncvbfgcfasdc', '2023-08-21 02:48:13', '2023-08-21 06:52:05'),
(13, 'S-2023-07-06-1', 'General Inquiry', 'Low', 'open', 'Bug Report', 'tcgfncvbfgcfasdc', '2023-08-21 02:48:15', '2023-08-21 06:52:34'),
(15, 'S-2023-07-06-1', 'Feature Request', 'High', 'in progress', 'sdafs', 'fasfsadfd', '2023-08-21 02:53:50', '2023-08-21 06:54:50'),
(17, 'B-2023-06-28-1', 'Bug Report', 'Low', 'open', 'Question', 'I have a question to you admin?', '2023-08-21 11:02:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ticketresponse`
--

CREATE TABLE `ticketresponse` (
  `response_id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_uniqueid` varchar(100) DEFAULT NULL,
  `admin_uniqueid` text DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticketresponse`
--

INSERT INTO `ticketresponse` (`response_id`, `ticket_id`, `user_uniqueid`, `admin_uniqueid`, `message`, `created_at`) VALUES
(3, 12, NULL, '23', '<p>hello there&nbsp;</p>', '2023-08-21 06:47:03'),
(4, 12, NULL, '23', '<p>hello po&nbsp;</p>', '2023-08-21 06:52:05'),
(5, 13, NULL, '23', '<p>ano reklamo niyo</p>', '2023-08-21 06:52:24'),
(6, 12, 'S-2023-07-06-1', NULL, '<p>may reklamo ako&nbsp;</p>', '2023-08-21 07:56:06'),
(7, 13, 'S-2023-07-06-1', NULL, '<p>hindi maganda produkto niyo</p>', '2023-08-21 07:56:43'),
(8, 17, 'B-2023-06-28-1', NULL, '<p>hello admin&nbsp;</p>', '2023-08-21 11:19:38'),
(9, 17, NULL, '23', '<p>ano magagawa ko sainyo?</p>', '2023-08-21 11:20:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buyer_accounts`
--
ALTER TABLE `buyer_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buyer_bids`
--
ALTER TABLE `buyer_bids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `harvest_schedulefk` (`harvest_schedule_id`),
  ADD KEY `buyer_idfk` (`buyer_id`);

--
-- Indexes for table `cart_list`
--
ALTER TABLE `cart_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `fk_product_id` (`product_id`);

--
-- Indexes for table `category_list`
--
ALTER TABLE `category_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `harvest_schedule`
--
ALTER TABLE `harvest_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_idfk` (`seller_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_buyer_id` (`buyer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD KEY `order_id_fk` (`order_id`),
  ADD KEY `product_id_fk` (`product_id`);

--
-- Indexes for table `order_list`
--
ALTER TABLE `order_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_id_fk` (`buyer_id`),
  ADD KEY `fk_order_list_seller` (`seller_id`),
  ADD KEY `idx_order_list_order_code` (`order_code`);

--
-- Indexes for table `product_list`
--
ALTER TABLE `product_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id_FK` (`category_id`),
  ADD KEY `seller_id_FK` (`seller_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_idfk` (`product_id`);

--
-- Indexes for table `seller_accounts`
--
ALTER TABLE `seller_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `ticketresponse`
--
ALTER TABLE `ticketresponse`
  ADD PRIMARY KEY (`response_id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `buyer_accounts`
--
ALTER TABLE `buyer_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `buyer_bids`
--
ALTER TABLE `buyer_bids`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `cart_list`
--
ALTER TABLE `cart_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `category_list`
--
ALTER TABLE `category_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `harvest_schedule`
--
ALTER TABLE `harvest_schedule`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_list`
--
ALTER TABLE `order_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `product_list`
--
ALTER TABLE `product_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `seller_accounts`
--
ALTER TABLE `seller_accounts`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `ticketresponse`
--
ALTER TABLE `ticketresponse`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buyer_bids`
--
ALTER TABLE `buyer_bids`
  ADD CONSTRAINT `buyer_idfk` FOREIGN KEY (`buyer_id`) REFERENCES `buyer_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `harvest_schedulefk` FOREIGN KEY (`harvest_schedule_id`) REFERENCES `harvest_schedule` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_list`
--
ALTER TABLE `cart_list`
  ADD CONSTRAINT `buyer_id` FOREIGN KEY (`buyer_id`) REFERENCES `buyer_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_product_id` FOREIGN KEY (`product_id`) REFERENCES `product_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `harvest_schedule`
--
ALTER TABLE `harvest_schedule`
  ADD CONSTRAINT `seller_idfk` FOREIGN KEY (`seller_id`) REFERENCES `seller_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_buyer_id` FOREIGN KEY (`buyer_id`) REFERENCES `buyer_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_id_fk` FOREIGN KEY (`order_id`) REFERENCES `order_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `product_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_list`
--
ALTER TABLE `order_list`
  ADD CONSTRAINT `buyer_id_fk` FOREIGN KEY (`buyer_id`) REFERENCES `buyer_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_list_seller` FOREIGN KEY (`seller_id`) REFERENCES `seller_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_list`
--
ALTER TABLE `product_list`
  ADD CONSTRAINT `category_id_FK` FOREIGN KEY (`category_id`) REFERENCES `category_list` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `seller_id_FK` FOREIGN KEY (`seller_id`) REFERENCES `seller_accounts` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_idfk` FOREIGN KEY (`product_id`) REFERENCES `order_items` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `ticketresponse`
--
ALTER TABLE `ticketresponse`
  ADD CONSTRAINT `ticketresponse_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `ticket` (`ticket_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
