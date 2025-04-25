-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 03:59 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `BRAND_ID` int(11) NOT NULL,
  `BRAND_NAME` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`BRAND_ID`, `BRAND_NAME`) VALUES
(1, 'APPLE'),
(2, 'SONY'),
(3, 'JBL'),
(4, 'BOAT'),
(5, 'PROTAN'),
(6, 'REDMI'),
(7, 'VIVO'),
(8, 'BOULT');

-- --------------------------------------------------------

--
-- Table structure for table `cancellations`
--

CREATE TABLE `cancellations` (
  `ID` int(11) NOT NULL,
  `CUSTOMER_ID` int(11) DEFAULT NULL,
  `CANCELLATION_TYPE` varchar(50) DEFAULT NULL,
  `ORDER_ID` int(11) DEFAULT NULL,
  `SERVICE_ID` int(11) DEFAULT NULL,
  `MOBILE_NUMBER` varchar(20) DEFAULT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `ADDRESS` text DEFAULT NULL,
  `REASON` text DEFAULT NULL,
  `DATE` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `CART_ID` int(11) NOT NULL,
  `CUSTOMER_ID` int(11) NOT NULL,
  `STATUS` varchar(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`CART_ID`, `CUSTOMER_ID`, `STATUS`) VALUES
(19, 4, 'Active'),
(20, 2, 'Active'),
(21, 1, 'Active'),
(22, 13, 'Active'),
(23, 14, 'Active'),
(24, 15, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `cart_details`
--

CREATE TABLE `cart_details` (
  `C_ID` int(11) NOT NULL,
  `CART_ID` int(11) NOT NULL,
  `PRODUCT_ID` int(11) NOT NULL,
  `QUANTITY` int(11) NOT NULL DEFAULT 1,
  `PRICE` decimal(10,2) NOT NULL DEFAULT 0.00,
  `STATUS` varchar(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_details`
--

INSERT INTO `cart_details` (`C_ID`, `CART_ID`, `PRODUCT_ID`, `QUANTITY`, `PRICE`, `STATUS`) VALUES
(93, 19, 1, 2, 59499.15, 'Active'),
(94, 19, 5, 1, 16000.00, 'Active'),
(95, 19, 1, 2, 59499.15, 'Active'),
(96, 20, 1, 1, 63749.15, 'Active'),
(97, 20, 1, 1, 59499.15, 'Active'),
(98, 20, 1, 1, 63749.15, 'Active'),
(99, 20, 1, 1, 59499.15, 'Active'),
(100, 20, 1, 1, 63749.15, 'Active'),
(101, 20, 1, 1, 59499.15, 'Active'),
(102, 20, 1, 2, 59499.15, 'Active'),
(103, 20, 1, 2, 63749.15, 'Active'),
(104, 20, 5, 1, 16000.00, 'Active'),
(105, 19, 1, 2, 59499.15, 'Active'),
(106, 19, 1, 2, 63749.15, 'Active'),
(107, 19, 1, 1, 63749.15, 'Active'),
(108, 19, 5, 1, 16000.00, 'Active'),
(109, 21, 1, 1, 59499.15, 'Active'),
(110, 21, 1, 1, 59499.15, 'Active'),
(111, 21, 1, 1, 59499.15, 'Active'),
(112, 21, 5, 1, 16000.00, 'Active'),
(113, 21, 3, 1, 599.00, 'Active'),
(114, 21, 5, 2, 16000.00, 'Active'),
(115, 21, 1, 3, 59499.15, 'Active'),
(116, 22, 5, 1, 16000.00, 'Active'),
(117, 22, 1, 1, 59499.15, 'Active'),
(118, 22, 5, 1, 16000.00, 'Active'),
(119, 20, 5, 1, 16000.00, 'Active'),
(120, 20, 1, 1, 59499.15, 'Active'),
(121, 20, 6, 1, 24000.00, 'Active'),
(122, 20, 5, 1, 16000.00, 'Active'),
(123, 20, 3, 1, 599.00, 'Active'),
(124, 20, 5, 1, 16000.00, 'Active'),
(125, 20, 5, 2, 16000.00, 'Active'),
(126, 20, 5, 2, 16000.00, 'Active'),
(127, 20, 1, 1, 59499.15, 'Active'),
(128, 22, 1, 1, 59499.15, 'Active'),
(129, 22, 5, 1, 16000.00, 'Active'),
(130, 23, 5, 2, 16000.00, 'Active'),
(131, 23, 8, 1, 599.00, 'Active'),
(132, 23, 5, 1, 16000.00, 'Active'),
(133, 23, 6, 1, 24000.00, 'Active'),
(134, 23, 2, 1, 8000.00, 'Active'),
(135, 24, 5, 2, 16000.00, 'Active'),
(136, 24, 7, 1, 1280.00, 'Active'),
(137, 24, 7, 1, 1280.00, 'Active'),
(138, 24, 7, 1, 1280.00, 'Active'),
(139, 24, 2, 1, 8000.00, 'Active'),
(140, 21, 1, 1, 59499.15, 'Active'),
(141, 21, 1, 1, 59499.15, 'Active'),
(142, 21, 1, 1, 59499.15, 'Active'),
(143, 21, 5, 1, 16000.00, 'Active'),
(144, 21, 8, 5, 599.00, 'Active'),
(145, 21, 4, 1, 7999.00, 'Active'),
(146, 21, 2, 1, 8000.00, 'Active'),
(147, 21, 9, 1, 87900.00, 'Active'),
(148, 21, 1, 1, 59499.15, 'Active'),
(149, 21, 1, 1, 59499.15, 'Active'),
(150, 21, 9, 1, 87900.00, 'Active'),
(151, 21, 8, 1, 599.00, 'Active'),
(152, 21, 8, 1, 599.00, 'Active'),
(153, 21, 7, 1, 1280.00, 'Active'),
(154, 21, 7, 1, 1280.00, 'Active'),
(155, 21, 16, 1, 60900.00, 'Active'),
(156, 21, 16, 1, 60900.00, 'Active'),
(157, 21, 1, 1, 59499.15, 'Active'),
(158, 21, 16, 1, 53592.00, 'Active'),
(159, 21, 16, 1, 53592.00, 'Active'),
(160, 21, 3, 1, 599.00, 'Active'),
(161, 21, 1, 1, 71315.00, 'Active'),
(162, 21, 16, 1, 53592.00, 'Active'),
(163, 21, 7, 2, 1280.00, 'Active'),
(164, 21, 4, 1, 7999.00, 'Active'),
(165, 21, 1, 3, 59499.15, 'Active'),
(166, 21, 16, 2, 53592.00, 'Active'),
(167, 21, 2, 1, 8000.00, 'Active'),
(168, 21, 16, 2, 53592.00, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CATEGORY_ID` int(11) NOT NULL,
  `CATEGORY_NAME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CATEGORY_ID`, `CATEGORY_NAME`) VALUES
(1, 'Mobiles'),
(2, 'CHARGERS'),
(3, 'HEADPHONES'),
(4, 'EARPHONES/BUDS'),
(5, 'VR-BOXES'),
(6, 'TABLETS'),
(8, 'Watch'),
(9, 'other');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CUSTOMER_ID` int(11) NOT NULL,
  `CUSTOMER_NAME` varchar(250) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `PHONE` varchar(10) NOT NULL,
  `ADDRESS` varchar(250) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `STATUS` enum('active','inactive') NOT NULL DEFAULT 'active',
  `usertype` enum('customer') NOT NULL DEFAULT 'customer',
  `REGISTER_DATE` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CUSTOMER_ID`, `CUSTOMER_NAME`, `EMAIL`, `PHONE`, `ADDRESS`, `PASSWORD`, `STATUS`, `usertype`, `REGISTER_DATE`) VALUES
(1, 'chirag', 'chirag1234@gmail.com', '9928364926', '28 durganagar silvercity', '$2y$10$Nn2QLp3zOG9NjQ10Q9AYT.sbpTpw/o2vUUfbxcvoGg0EUhGNso5Vq', 'inactive', 'customer', '2025-03-02'),
(2, 'lewis hamilton', 'lewisham@gmail.com', '9157091055', 'BRIDGETOWN ENGLAND', '$2y$10$jp2WI/hLRvuRKJrYiBhKKeE/LLN03Zmio9JVKWopCzUHsHmyZnJVW', 'inactive', 'customer', '2025-03-02'),
(3, 'HARSH SHANKHLA', 'harshshankhala99@gmail.com', '9157091055', '', '$2y$10$7t/E2YiwnOcQ3zr7mhGTh.Qy1XPfcpvoQY6jyYzHIGqDnZbVBiP4a', 'inactive', 'customer', '2025-03-02'),
(4, 'KRISHNA', 'KKS3457@gmail.com', '3453443434', '', '$2y$10$3Ht43xukLUyFL.VTWcQiouO72MJGV4fQYJTZVtP8t3IGXhJMsYiNO', 'inactive', 'customer', '2025-03-02'),
(5, 'BHEEM', 'bheem@gmail.com', '3453534534', '', '$2y$10$wY/faVpdehvo7OKxWsQ/7e6pty8wOzxwyx2CLUfVRcrk.JFSKmBpS', 'inactive', 'customer', '2025-04-02'),
(6, 'Thirth Shrimali', 'thirth@123gmail.com', '9913124353', '', '$2y$10$vAMyAWJBSJV2g4cjXKDo5OAUaDsBMKBtah2GiLE5Qp82rcMNZgH9O', 'inactive', 'customer', '2025-04-02'),
(7, 'Ash', 'familybokade176@gmail.com', '9928364926', '', '$2y$10$AtW5m5Lp.dG78KYO0po/r.xyqRJl/f6jYbQ5O4r3q8xIJ1uzqk/7.', 'inactive', 'customer', '2025-04-02'),
(8, 'Manoj', 'manoj123@gmail.com', '+918000696', '', '$2y$10$R2jWNXhwi157SmIy2lSirO/RoIAk9PJVzwDzVipz6HUD37gbqk4py', 'inactive', 'customer', '2025-04-02'),
(9, 'vimal', 'vimal@100gmail.com', '2342543453', '', '$2y$10$Jte9/j37vN3ByGH/5NoqZ.TucfRDxntbpgggth3L5BaW.xpdhZaRq', 'inactive', 'customer', '2025-04-02'),
(10, 'jonnie', 'jonnie@gmail.com', '9157091055', '', '$2y$10$Yuv3wTnlUkvkah4oOhbyiu3L6YxhDe2wvtIZToKa7aPXxXnGFL2mC', 'inactive', 'customer', '2025-04-02'),
(11, ' vibhuti mishra', 'vmishra@gmail.com', '3453534534', '', '$2y$10$2jwb4xieY6i/4XbQgJq5XOcONpyKirB.OTOI.WjkLDlpQQQvPeNvO', 'inactive', 'customer', '2025-04-02'),
(12, 'john rambo', 'johnrambo@gmail.com', '2352352342', '', '$2y$10$0f/.SCZIdR3BDuuo.TQt9.BIe2YCodqrvgguYz.lxR.XtHe6VWdHC', 'inactive', 'customer', '2025-04-02'),
(13, 'fernando alonso', 'fernando@gmail.com', '9157091055', 'A/30 MAURTI TENAMENT VASTRAL AHEMDABAD', '$2y$10$zJeZqiDLG74jY18JITjeleObU8l9dqojg50tfDL1SXO6UlzksGg6K', 'inactive', 'customer', '2025-04-02'),
(14, 'ramcharan', 'ramcharanc@gmail.com', '2837283323', 'A/79 MAURTI TENAMENT VASTRAL AHEMDABAD', '$2y$10$SZsi7/Q/oR.LMN97hlhAReFGBTV5OJ0ZUr/opcqQJz7SO1ez7cpuK', 'inactive', 'customer', '2025-04-02'),
(15, 'shinchan nohara', 'shinchan@gmail.com', '9157091017', 'A/77 BAJRANG KRUPA APPARTMENTS VASTRAL AHEMDABAD', '$2y$10$u5xSuMyOF0SBdhXCPY0FqOraKmNcikaWnMbHd6PFIQXNSRQx6wmKC', 'inactive', 'customer', '2025-03-02');

-- --------------------------------------------------------

--
-- Table structure for table `feedback_complaints`
--

CREATE TABLE `feedback_complaints` (
  `ID` int(11) NOT NULL,
  `FEEDBACK_TYPE` varchar(50) NOT NULL,
  `TYPE` varchar(50) NOT NULL,
  `NAME` varchar(100) DEFAULT NULL,
  `CONTACT_METHOD` varchar(50) DEFAULT NULL,
  `PHONE` varchar(20) DEFAULT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `DETAILS` text NOT NULL,
  `OUTCOMES` text DEFAULT NULL,
  `SUBMISSION_DATE` timestamp NOT NULL DEFAULT current_timestamp(),
  `SERVICE_ID` int(11) DEFAULT NULL,
  `ORDER_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback_complaints`
--

INSERT INTO `feedback_complaints` (`ID`, `FEEDBACK_TYPE`, `TYPE`, `NAME`, `CONTACT_METHOD`, `PHONE`, `EMAIL`, `DETAILS`, `OUTCOMES`, `SUBMISSION_DATE`, `SERVICE_ID`, `ORDER_ID`) VALUES
(12, 'Complaint', 'product', 'bokade chirag jitendrabhai', 'Phone', '09924671098', 'chirag1234@gmail.com', 'My product is not here', 'okk your product will deliverd to you as soon as possible', '2025-02-08 05:34:50', NULL, 90);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `userType` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `email`, `password`, `userType`) VALUES
(1, 'bokdemehul870@gmail.com', 'Mehul@1940', 'admin'),
(2, 'harsh123@gmail.com', 'harsh123', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `offer`
--

CREATE TABLE `offer` (
  `OFFER_ID` int(11) NOT NULL,
  `PRODUCT_ID` int(11) DEFAULT NULL,
  `OFFER_NAME` varchar(100) DEFAULT NULL,
  `START_DATE` date NOT NULL,
  `END_DATE` date NOT NULL,
  `DISCOUNT_RATE` int(11) NOT NULL,
  `DESCRIPTION` varchar(250) DEFAULT NULL,
  `OFFER_IMG` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offer`
--

INSERT INTO `offer` (`OFFER_ID`, `PRODUCT_ID`, `OFFER_NAME`, `START_DATE`, `END_DATE`, `DISCOUNT_RATE`, `DESCRIPTION`, `OFFER_IMG`) VALUES
(1, 1, 'SUPER SALE', '2025-02-01', '2025-04-19', 15, 'AMAZING OFFER ON IPHONE', '/Project/uploads/IB162.jpg'),
(3, 16, 'Monthly Offer', '2025-03-16', '2025-04-24', 12, 'Come and get the iphone 15 with 12% offer', '/Project/uploads/i15.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `ORDER_ID` int(11) NOT NULL,
  `CUSTOMER_ID` int(11) NOT NULL,
  `TOTAL_AMT` decimal(10,2) NOT NULL,
  `DELIVERY_ADDRESS` varchar(255) NOT NULL,
  `DELIVERY_STATUS` enum('Pending','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  `ORDER_DATE` timestamp NOT NULL DEFAULT current_timestamp(),
  `PAYMENT_METHOD` varchar(50) NOT NULL DEFAULT 'COD'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`ORDER_ID`, `CUSTOMER_ID`, `TOTAL_AMT`, `DELIVERY_ADDRESS`, `DELIVERY_STATUS`, `ORDER_DATE`, `PAYMENT_METHOD`) VALUES
(88, 2, 262496.60, 'JAY MATAJI VILLA', 'Delivered', '2025-01-02 01:54:50', 'COD'),
(89, 4, 198747.45, 'JASHODANAGAR', 'Delivered', '2025-02-08 02:01:12', 'COD'),
(90, 1, 16599.00, '28 DURGANAGAR Silvercity', 'Delivered', '2025-02-08 05:28:13', 'COD'),
(91, 13, 166998.30, 'a/27 maruti tenament vastral ahemdabad', 'Pending', '2025-02-25 11:45:44', 'COD'),
(92, 2, 210497.45, 'FERNANDO\'S ADDRESS', 'Pending', '2025-02-25 11:59:05', 'COD'),
(93, 2, 80000.00, '', 'Pending', '2025-02-25 12:07:20', 'COD'),
(94, 2, 32599.00, 'MANEK CHOWK', 'Pending', '2025-02-25 12:08:22', 'COD'),
(95, 2, 32000.00, '', 'Pending', '2025-02-25 12:10:44', 'COD'),
(96, 2, 178497.45, '', 'Pending', '2025-02-25 12:14:33', 'COD'),
(97, 13, 134998.30, 'A/27 MAURTI TENAMENT VASTRAL AHEMDABAD', 'Pending', '2025-02-25 12:21:20', 'COD'),
(98, 14, 56599.00, 'A/29 MAURTI TENAMENT VASTRAL AHEMDABAD', 'Pending', '2025-02-25 12:24:36', 'COD'),
(99, 14, 8000.00, 'a/30 maruti tenament', 'Pending', '2025-02-25 12:25:25', 'COD'),
(100, 15, 42560.00, 'A/77 BAJRANG KRUPA APPARTMENTS VASTRAL AHEMDABAD', 'Pending', '2025-02-25 15:42:08', 'COD'),
(101, 1, 53592.00, 'B/126 DURGANAGAR TENAMENT SILVERCITY AHEMDABAD', 'Shipped', '2025-03-23 08:29:03', 'COD'),
(102, 1, 178497.45, '28 durganagar silvercity', 'Pending', '2025-04-15 05:31:54', 'COD'),
(103, 1, 115184.00, '28 durganagar silvercity', 'Pending', '2025-04-21 14:32:13', 'COD'),
(104, 1, 160776.00, '28 durganagar silvercity', 'Pending', '2025-04-21 14:53:47', 'COD');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `ORDER_ITEM_ID` int(11) NOT NULL,
  `ORDER_ID` int(11) NOT NULL,
  `PRODUCT_ID` int(11) NOT NULL,
  `QUANTITY` int(11) NOT NULL,
  `PRICE` decimal(10,2) NOT NULL,
  `VARIANT_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`ORDER_ITEM_ID`, `ORDER_ID`, `PRODUCT_ID`, `QUANTITY`, `PRICE`, `VARIANT_ID`) VALUES
(111, 88, 1, 2, 59499.15, 1),
(112, 88, 1, 2, 63749.15, 3),
(113, 88, 5, 1, 16000.00, NULL),
(114, 89, 1, 2, 59499.15, 1),
(115, 89, 1, 1, 63749.15, 3),
(116, 89, 5, 1, 16000.00, NULL),
(117, 90, 5, 1, 16000.00, NULL),
(118, 90, 3, 1, 599.00, NULL),
(119, 91, 5, 3, 16000.00, NULL),
(120, 91, 1, 2, 59499.15, 1),
(121, 92, 5, 2, 16000.00, NULL),
(122, 92, 1, 3, 59499.15, 1),
(123, 93, 6, 2, 24000.00, NULL),
(124, 93, 5, 2, 16000.00, NULL),
(125, 94, 3, 1, 599.00, NULL),
(126, 94, 5, 2, 16000.00, NULL),
(127, 95, 5, 2, 16000.00, NULL),
(128, 96, 1, 3, 59499.15, 1),
(129, 97, 1, 2, 59499.15, 1),
(130, 97, 5, 1, 16000.00, NULL),
(131, 98, 5, 2, 16000.00, NULL),
(132, 98, 8, 1, 599.00, NULL),
(133, 98, 6, 1, 24000.00, NULL),
(134, 99, 2, 1, 8000.00, 5),
(135, 100, 5, 2, 16000.00, NULL),
(136, 100, 7, 2, 1280.00, NULL),
(137, 100, 2, 1, 8000.00, 5),
(138, 101, 16, 1, 53592.00, NULL),
(139, 102, 1, 3, 59499.15, 1),
(140, 103, 16, 2, 53592.00, NULL),
(141, 103, 2, 1, 8000.00, 5),
(142, 104, 16, 3, 53592.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `PRODUCT_ID` int(11) NOT NULL,
  `PRODUCT_NAME` varchar(100) NOT NULL,
  `CATEGORY_ID` int(11) DEFAULT NULL,
  `BRAND_ID` int(11) DEFAULT NULL,
  `PRICE` decimal(10,2) NOT NULL,
  `P_IMG` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`PRODUCT_ID`, `PRODUCT_NAME`, `CATEGORY_ID`, `BRAND_ID`, `PRICE`, `P_IMG`) VALUES
(1, 'IPHONE16', 1, 1, 69000.00, 'imgs/product/IB162.jpg'),
(2, 'BOAT-C3 HEADPHONES', 3, 4, 6999.00, 'imgs/product/boatb3.jpg'),
(3, 'JBL-C50HI EARPHONES', 4, 3, 599.00, 'imgs/product/blackjbl.jpg'),
(4, 'HORIZONVR 360', 5, 2, 7999.00, 'imgs/product/vr1.jpg'),
(5, 'REDMI K40', 1, 6, 20000.00, 'imgs/product/BC1.jpg'),
(6, 'IQOO V12', 1, 7, 24000.00, 'imgs/product/IQB1.jpg'),
(7, 'BOULT MUSTANG TWS EARBUDS(TORQ)', 4, 8, 1280.00, 'imgs/product/M1.jpg'),
(8, 'PROTAN 65W GAN CHARGER', 2, 5, 599.00, 'imgs/product/PT1.jpg'),
(9, 'iPhone 11 Pro Max', 1, 1, 87900.00, 'imgs/product/ipm.jpg'),
(16, ' iPhone 15 ', 1, 1, 60900.00, 'imgs/product/i15.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_details`
--

CREATE TABLE `product_details` (
  `PPID` int(11) NOT NULL,
  `PRODUCT_ID` int(11) DEFAULT NULL,
  `IMG` varchar(255) NOT NULL,
  `SMALL_IMG` varchar(255) DEFAULT NULL,
  `DES` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_details`
--

INSERT INTO `product_details` (`PPID`, `PRODUCT_ID`, `IMG`, `SMALL_IMG`, `DES`) VALUES
(1, 1, '/project/imgs/product/IB161.jpg', '[\"\\/project\\/imgs\\/product\\/IB162.jpg\",\"\\/project\\/imgs\\/product\\/IB161.jpg\",\"\\/project\\/imgs\\/product\\/IB164 (2).jpg\",\"\\/project\\/imgs\\/product\\/IB3.jpg\"]', 'About this item\r\nSTUNNING TITANIUM DESIGN — iPhone 16 Pro has a strong and light titanium design with a larger 15.93 cm (6.3″) Super Retina XDR display. It’s remarkably durable with the latest-generation Ceramic Shield material that’s 2x tougher than any smartphone glass.\r\nBUILT FOR APPLE INTELLIGENCE — Apple Intelligence is the personal intelligence system that helps you write, express yourself and get things done effortlessly. With groundbreaking privacy protections, it gives you peace of mind that no one else can access your data — not even Apple.\r\nTAKE TOTAL CAMERA CONTROL — Camera Control gives you an easier way to quickly access camera tools, like zoom or depth of field, so you can take the perfect shot in record time.\r\nMAGNIFICENT SHOTS — Take your videos to a whole new level with 4K 120 fps Dolby Vision, enabled by the 48MP Fusion camera. The improved 48MP Ultra Wide camera lets you capture mesmerising detail in macro photos and wide-angle shots.\r\nPHOTOGRAPHIC STYLES — The latest-generation Photographic Styles give you greater creative flexibility, so you can make every photo even more you. And thanks to advances in the image pipeline, you can now reverse any style, any time.'),
(2, 2, '/project/imgs/product/boatb3.jpg', '[\"\\/project\\/imgs\\/product\\/boatb1.jpg\",\"\\/project\\/imgs\\/product\\/boatb2.jpg\",\"\\/project\\/imgs\\/product\\/BOATB5.jpg\",\"\\/project\\/imgs\\/product\\/BOATB6.jpg\",\"\\/project\\/imgs\\/product\\/boatb4.jpg\"]', 'About this item\r\nPlayback- The mighty 500mAh battery capacity offers a superior playback time of up to 20 Hours\r\nDrivers- Its 50mm dynamic drivers help pump out immersive audio all day long\r\nEar Cushions- It has been ergonomically designed and structured as an over-ear headphone to provide the best user experience with its plush padded earcushions\r\nPhysical Noise Isolation- It comes with physical Noise Isolation feature for pure audio bliss\r\nPlayback- It provides a massive battery backup of upto 15 hours for a superior playback time. Charging Time : 3 Hours\r\nDrivers- Its 40mm dynamic drivers help pump out immersive HD audio all day long.\r\nEarcushions- It has been ergonomically designed and structured as an on-ear headphone to provide the best user experience with its comfortable padded earcushions and lightweight design\r\nControls- You can control your music without hiccups using the easy access controls, communicate seamlessly using the built-in mic, access voice assistant and always stay in the zone'),
(3, 3, '/project/imgs/product/blackjbl.jpg', '[\"\\/project\\/imgs\\/product\\/blackjbl2.jpg\",\"\\/project\\/imgs\\/product\\/blackjbl.jpg\",\"\\/project\\/imgs\\/product\\/bluejbl.jpg\",\"\\/project\\/imgs\\/product\\/bluejbl2.jpg\"]', 'About this item\r\nJBL Signature Sound\r\nHigh Clean Bass\r\nNoise Isolation Microphone\r\nOne-Button Universal Remote with Mic\r\nQuick Launch Access to Google Assistant / Siri\r\nUltra Lightweight and Comfortable with 3 sizes of ear tips\r\nHigh Fidelity Twin Cable\r\nWhat&#039;s in the box : 1 pair JBL C50HI headphone, 3 sets of ear tips (S, M, L), 1 Warranty and safety card'),
(4, 4, '/project/imgs/product/vr1.jpg', '[\"\\/project\\/imgs\\/product\\/vr1_resized.jpg\",\"\\/project\\/imgs\\/product\\/vr1.jpg\",\"\\/project\\/imgs\\/product\\/horizonvr1.jpg\",\"\\/project\\/imgs\\/product\\/horizonvr2.jpg\"]', 'About this item\r\nWatch Live Cricket, movies, TV shows, and YouTube videos in 360° (for Android users only), 3D games &amp; learning apps via JioImmerse. JioImmerse app is the gateway to 360° VR content\r\nAccess to the largest collection of 360° VR content &amp; experiences. Watch 6,000+ movies, 1,000+ TV shows with JioCinema &amp; 1,000+ Live TV channels with JioTV XR\r\nCan watch movies &amp; TV shows comfortably while lying down or in a seated position\r\nJioDive can be worn comfortably over spectacles\r\nAccessible to all Indian phone numbers. Works with every network &amp; WiFi\r\nSupports 700+ smartphones with phone sizes up to 6.7 inches, supports Android 9+ and iOS 15+ with Gyroscope &amp; Accelerometer sensors. Please check phone compatibility on the JioDive website\r\nAdjust the side wheels to fix blurry picture quality and adjust the center wheel to fix double imaging. Please note the quality of content is dependent on the display resolution of your smartphone and the internet speed'),
(5, 5, '/project/imgs/product/BC1.jpg', '[\"\\/project\\/imgs\\/product\\/BC2.jpg\",\"\\/project\\/imgs\\/product\\/BC1.jpg\",\"\\/project\\/imgs\\/product\\/BC3.jpg\"]', 'About this item\r\nSTUNNING TITANIUM DESIGN — iPhone 16 Pro has a strong and light titanium design with a larger 15.93 cm (6.3″) Super Retina XDR display. It’s remarkably durable with the latest-generation Ceramic Shield material that’s 2x tougher than any smartphone glass.\r\nBUILT FOR APPLE INTELLIGENCE — Apple Intelligence is the personal intelligence system that helps you write, express yourself and get things done effortlessly. With groundbreaking privacy protections, it gives you peace of mind that no one else can access your data — not even Apple.\r\nTAKE TOTAL CAMERA CONTROL — Camera Control gives you an easier way to quickly access camera tools, like zoom or depth of field, so you can take the perfect shot in record time.\r\nMAGNIFICENT SHOTS — Take your videos to a whole new level with 4K 120 fps Dolby Vision, enabled by the 48MP Fusion camera. The improved 48MP Ultra Wide camera lets you capture mesmerising detail in macro photos and wide-angle shots.'),
(6, 6, '/project/imgs/product/IQB1.jpg', '[\"\\/project\\/imgs\\/product\\/IQB3.jpg\",\"\\/project\\/imgs\\/product\\/IQB1.jpg\",\"\\/project\\/imgs\\/product\\/IQB2.jpg\"]', 'About this item\r\n[Compatibility] Spigen Genuine Case Compatible with iPhone 16 Pro\r\n[Modern design] Modern style pattern design for fingerprint resistance and minimal look\r\n[Protection] Air Cushion Technology for shock absorption\r\n[Precise Fit] Form-fitted to maintain a slim profile and pocket-friendly\r\n[Material] Made of high-quality TPU'),
(7, 7, '/project/imgs/product/M1.jpg', '[\"\\/project\\/imgs\\/product\\/M1_resized.jpg\",\"\\/project\\/imgs\\/product\\/M1.jpg\",\"\\/project\\/imgs\\/product\\/M3.jpg\",\"\\/project\\/imgs\\/product\\/M2.jpg\",\"\\/project\\/imgs\\/product\\/M8.jpg\"]', 'About this item\r\n✅ Boult X Mustang - Speed Meets Sound: A fusion of Mustang’s iconic speed &amp; power with Boult’s advanced audio tech. Bold yellow racing aesthetics meet cutting-edge performance for an exhilarating sound experience.\r\n✅ BOULT Amp App Connectivity: Take control with custom EQ modes, gesture customization, and Quick Cleanup for optimized performance. Tailor your listening experience to perfection.\r\n✅ Zen Quad Mic ENC: Four precision microphones with Environmental Noise Cancellation (ENC) ensure ultra-clear calls and superior voice isolation in any setting.\r\n✅ 60 Hours Playtime: Power through your music, calls, and gaming marathons with a massive 60-hour battery life—designed for non-stop entertainment.\r\n✅ 45ms Ultra-Low Latency: Stay ahead in gaming with 45ms low latency, ensuring real-time audio sync for an edge in competitive gaming and seamless media playback.\r\n✅ BT 5.4 with Blink &amp; Pair: The latest Bluetooth 5.4 delivers instant pairing, stable connectivity, and power-efficient transmission for uninterrupted listening.\r\n✅ Customizable EQ Modes: Tune your audio experience with multiple EQ presets, adjusting bass, treble, and mids to suit your listening style.'),
(8, 8, '/project/imgs/product/PT1.jpg', '[\"\\/project\\/imgs\\/product\\/PT1_resized.jpg\",\"\\/project\\/imgs\\/product\\/PT1.jpg\",\"\\/project\\/imgs\\/product\\/PT3.jpg\",\"\\/project\\/imgs\\/product\\/PT2.jpg\",\"\\/project\\/imgs\\/product\\/PT4.jpg\",\"\\/project\\/imgs\\/product\\/PT5.jpg\"]', 'About this item\r\n[ 65W Fast Charger ] : Featuring Qualcomm Quick Charge 3.0 technology, it powers up your devices at remarkable speed, ensuring you stay connected with minimal downtime. Experience lightning-fast charging designed for oneplus charger 65w original\r\n[ 3X Fast Charging ] : Unlock superior speed with 3X Fast Charging Technology. Our advanced 65 watt charger delivers up to 3 times faster charging than standard 20w chargers. Stay charged and Ready\r\n[ Dash, VOOC, Super VOOC &amp; Warp Charging ] : Designed to support Advanced charging technologies like Dash, VOOC, Super VOOC, and Warp Charging. 65w charger ensuring ultra-fast and efficient power delivery for a wide range of devices\r\n[ BIS Certified &amp; Made in India ] : This BIS-certified charger ensures top-tier safety and quality standards. Made in India 65w charger type c combines quality with innovation, supporting local craftsmanship\r\n[ Safe &amp; Reliable ] : Kratos Turbo Charger combines Speed and Safety. With Advanced Safety features like Over-voltage potection, Over-Heating protection &amp; Short Circuit Protection, type c charger adapter ensures safe and secure charging experience\r\n[ Universal Compatibility ] : This type c charger adapter is widely compatible with Oneplus 14R/14/13/12/12R /11/11R/10/10 Pro/9/9 Pro/ 9R/ 8/ 8T/7/Nord/CE 3 2,2t, Nothing,Realme,Redmi,Xiaomi,Oppo, Mi, Iqoo, Poco &amp; Other type c smartphones\r\n[ Premium Built ] : Designed for durability for oneplus 65 watt adapter features a robust build to endure daily wear. Made from high-quality materials, it ensures long-lasting performance, making it your reliable charging choice'),
(9, 9, '/project/imgs/product/ipm.jpg', '[\"\\/project\\/imgs\\/product\\/ipm1.jpg\",\"\\/project\\/imgs\\/product\\/ipm2.jpg\",\"\\/project\\/imgs\\/product\\/ipm3.jpg\",\"\\/project\\/imgs\\/product\\/ipm4.jpg\"]', 'About this item\r\n6.5-inch (16.5 cm diagonal) Super Retina XDR OLED display\r\nWater and dust resistant (4 meters for up to 30 minutes, IP68)\r\nTriple-camera system with 12MP Ultra Wide, Wide, and Telephoto cameras; Night mode, Portrait mode, and 4K video up to 60fps\r\n12MP TrueDepth front camera with Portrait Mode, 4K video, and Slo-Mo\r\nFace ID for secure authentication\r\nA13 Bionic chip with third-generation Neural Engine\r\nFast charge with 18W adapter included\r\nWireless charging\r\niOS with redesigned widgets on the Home screen, all-new App Library, App Clips and more'),
(10, 16, '/project/imgs/product/i15.jpg', '[\"\\/project\\/imgs\\/product\\/i15_1.jpg\",\"\\/project\\/imgs\\/product\\/i15_2.jpg\",\"\\/project\\/imgs\\/product\\/i15_3.jpg\",\"\\/project\\/imgs\\/product\\/i15.jpg\"]', 'About this item\r\nDYNAMIC ISLAND COMES TO IPHONE 15 — Dynamic Island bubbles up alerts and Live Activities — so you don’t miss them while you’re doing something else. You can see who’s calling, track your next ride, check your flight status, and so much more.\r\nINNOVATIVE DESIGN — iPhone 15 features a durable color-infused glass and aluminum design. It’s splash, water, and dust resistant. The Ceramic Shield front is tougher than any smartphone glass. And the 6.1&quot; Super Retina XDR display is up to 2x brighter in the sun compared to iPhone 14.\r\n48MP MAIN CAMERA WITH 2X TELEPHOTO — The 48MP Main camera shoots in super-high resolution. So it’s easier than ever to take standout photos with amazing detail. The 2x optical-quality Telephoto lets you frame the perfect close-up.\r\nNEXT-GENERATION PORTRAITS — Capture portraits with dramatically more detail and color. Just tap to shift the focus between subjects — even after you take the shot.\r\nPOWERHOUSE A16 BIONIC CHIP — The superfast chip powers advanced features like computational photography, fluid Dynamic Island transitions, and Voice Isolation for phone calls. And A16 Bionic is incredibly efficient to help deliver great all-day battery life.');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `product_name`, `user_name`, `rating`, `comment`, `review_date`) VALUES
(1, 'JBL C50HI EARPHONES', 'harsh', 4, 'this is the best product', '2025-02-04 04:24:27'),
(2, 'JBL C50HI EARPHONES', 'KRISHNA', 5, 'I AGREE HARSH', '2025-02-04 04:25:57'),
(3, 'IPHONE-16', 'lewis hamilton', 5, 'this is awesome belive me', '2025-02-04 04:53:17'),
(4, 'BOULT MUSTANG TWS EARBUDS(TORQ)', 'lewis hamilton', 5, 'this product is good\\r\\n', '2025-02-04 10:58:14'),
(6, 'PROTAN 65W GAN CHARGER', 'lewis hamilton', 3, 'nice charger', '2025-02-04 11:04:40'),
(13, 'BOAT C-3 HEADPHONES', 'lewis hamilton', 5, 'nice headphones\\r\\n', '2025-02-04 11:12:20'),
(14, 'PROTAN 65W GAN CHARGER', 'raghav', 3, 'bau mast che', '2025-02-04 11:19:05'),
(15, 'IPHONE16', 'lewis hamilton', 3, 'AWESOME PRODUCT', '2025-02-07 11:49:38'),
(16, 'JBL-C50HI EARPHONES', 'mehul', 4, 'PREETY GOOD', '2025-02-07 11:51:06'),
(17, 'REDMI K40', 'Chirag', 5, 'respect to bruce leec', '2025-03-05 17:42:32');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `VARIANT_ID` int(11) NOT NULL,
  `PRODUCT_ID` int(11) NOT NULL,
  `COLOR` varchar(255) NOT NULL,
  `RAM` varchar(50) NOT NULL,
  `STORAGE` varchar(50) NOT NULL,
  `PRICE` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`VARIANT_ID`, `PRODUCT_ID`, `COLOR`, `RAM`, `STORAGE`, `PRICE`) VALUES
(1, 1, 'BLACK', '8', '128', 69999.00),
(2, 1, 'BLUE', '8', '128', 69999.00),
(3, 1, 'CORE BLACK', '8', '512', 74999.00),
(4, 1, 'DESRT TITANIUM', '8', '512', 74999.00),
(5, 2, 'BLUE', '', '', 8000.00),
(6, 3, 'Red', '', '', 599.00),
(7, 3, 'Blue', '', '', 599.00),
(8, 1, 'pink', '256GB', '256GB', 83900.00);

-- --------------------------------------------------------

--
-- Table structure for table `review_shop`
--

CREATE TABLE `review_shop` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review_shop`
--

INSERT INTO `review_shop` (`id`, `name`, `email`, `message`, `submitted_at`) VALUES
(1, 'Krishna ', 'KKS3457@gmail.com', 'JKBK', '2025-01-30 06:20:40'),
(2, 'chirag bokade', 'chirag1234@gmail.com', 'I like this Product', '2025-02-01 13:08:31');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `SERVICE_ID` int(11) NOT NULL,
  `SERVICE_NAME` varchar(50) NOT NULL,
  `DESCRIPTION` varchar(50) NOT NULL,
  `PRICE` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`SERVICE_ID`, `SERVICE_NAME`, `DESCRIPTION`, `PRICE`) VALUES
(1, 'Screen Replacement', 'Screen Repair For Mobiles', 5999.00),
(2, 'Camera Repair', 'Screen Repair For Mobiles', 4999.00),
(3, 'Dead Pixel Issue Repair', 'Fix Dead Pixels From Screen', 399.00),
(4, 'Charging Socket Repair', 'Repair Charging Socket', 499.00);

-- --------------------------------------------------------

--
-- Table structure for table `service_bookings`
--

CREATE TABLE `service_bookings` (
  `booking_id` int(11) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `service_type` varchar(100) DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `booking_time` time DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `estimate` decimal(10,2) DEFAULT NULL,
  `status` enum('Pending','Estimated','Accepted','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_bookings`
--

INSERT INTO `service_bookings` (`booking_id`, `customer_name`, `customer_email`, `address`, `service_type`, `booking_date`, `booking_time`, `notes`, `estimate`, `status`, `created_at`) VALUES
(8, 'LEWIS HAMILTON', 'lewisham@gmail.com', NULL, 'Screen Replacement', '2025-01-31', '15:07:00', 'qcqwecq', 5000.00, 'Accepted', '2025-01-31 09:37:30'),
(9, 'Harsh ', 'harshshankhala5@gmail.com', NULL, 'Screen Replacement', '2025-01-31', '16:20:00', 'asvcads', NULL, 'Pending', '2025-01-31 10:56:08'),
(10, 'Harsh ', 'harshshankhala99@gmail.com', NULL, 'Camera Repair', '2025-01-10', '16:30:00', 'ASCAS', 5000.00, 'Accepted', '2025-01-31 10:58:11'),
(11, 'Harsh ', 'harshshankhala5@gmail.com', NULL, 'Screen Replacement', '2025-01-10', '16:32:00', 'SVDVS', 5999.00, 'Pending', '2025-01-31 11:03:01'),
(12, 'Krishna ', 'KKS3457@gmail.com', NULL, 'Camera Repair', '2025-02-07', '18:56:00', 'dfbdfbd', 4999.00, 'Pending', '2025-01-31 11:26:42'),
(13, 'phula', 'KPL@gmail.com', NULL, 'Screen Replacement', '2025-02-09', '09:33:00', 'asdfsdfsds', 5999.00, 'Rejected', '2025-01-31 14:03:43'),
(14, 'chutki', 'chutki@gmail.com', NULL, 'Screen Replacement', '2025-02-08', '12:49:00', 'srbdsfb', NULL, 'Pending', '2025-02-01 03:19:52'),
(15, 'old monk', 'lewisham@gmail.com', NULL, 'Screen Replacement', '2025-02-12', '09:03:00', 'sdvsd', NULL, 'Pending', '2025-02-01 03:30:24'),
(16, 'phula', 'KPL@gmail.com', NULL, 'Camera Repair', '2025-02-12', '09:08:00', 'sdvsd', 1000.00, 'Accepted', '2025-02-01 03:36:29'),
(17, 'chirag bokade', 'chirag1234@gmail.com', NULL, 'Screen Replacement', '2025-02-03', '19:35:00', 'it is very broken', NULL, 'Pending', '2025-02-01 12:03:56'),
(18, 'yaan mardinborough', 'michal@gmail.com', NULL, 'Camera Repair', '2025-02-04', '16:24:00', 'hello', NULL, 'Pending', '2025-02-03 09:54:27'),
(19, 'vimal', 'vimal@100gmail.com', NULL, 'Dead Pixel Issue Repair', '2025-02-03', '17:43:00', 'vimal bhai ka mobile kharab hai thik akro', NULL, 'Pending', '2025-02-03 10:14:11'),
(20, 'JONNIE WALKER', 'vimal@100gmail.com', NULL, 'Camera Repair', '2025-02-03', '20:18:00', 'dfbefb', NULL, 'Pending', '2025-02-03 13:47:53'),
(21, 'JONNIE WALKER', 'jonnie@gmail.com', NULL, 'Charging Socket Repair', '2025-02-04', '11:01:00', 'my socket is dead', NULL, 'Pending', '2025-02-04 02:28:23'),
(22, 'JONNIE WALKER', 'lewisham@gmail.com', NULL, 'Screen Replacement', '2025-02-07', '18:20:00', 'ASVCAS', NULL, 'Pending', '2025-02-07 11:50:10'),
(23, 'Krishna ', 'KKS3457@gmail.com', NULL, 'Screen Replacement', '2025-02-07', '21:35:00', 'dvs', 5000.00, 'Accepted', '2025-02-07 15:05:38'),
(24, 'phula', 'KKS3457@gmail.com', NULL, 'Screen Replacement', '2025-02-07', '20:42:00', 'asc', NULL, 'Pending', '2025-02-07 15:12:24'),
(25, 'JONNIE WALKER', 'KPL@gmail.com', NULL, 'Dead Pixel Issue Repair', '2025-02-07', '20:50:00', 'dvs', NULL, 'Pending', '2025-02-07 15:20:52'),
(26, 'Krishna ', 'KKS3457@gmail.com', NULL, 'Charging Socket Repair', '2025-02-07', '20:51:00', 'ere', NULL, 'Pending', '2025-02-07 15:21:42'),
(27, 'chain', 'KKS3457@gmail.com', NULL, 'Charging Socket Repair', '2025-02-07', '21:53:00', 'dvs', NULL, 'Pending', '2025-02-07 15:22:26'),
(28, 'fernando alonso', 'fernando@gmail.com', 'A/27 MAURTI TENAMENT VASTRAL AHEMDABAD', 'Screen Replacement', '2025-02-26', '20:46:00', 'ferrari', 5000.00, 'Rejected', '2025-02-25 10:51:59'),
(29, 'ramcharan', 'ramcharan@gmail.com', 'asc', 'Charging Socket Repair', '2025-02-27', '19:36:00', 'ad', 9999.00, 'Rejected', '2025-02-25 13:34:05'),
(30, 'shinchan nohara', 'shinchan@gmail.com', 'A/77 BAJRANG KRUPA APPARTMENTS VASTRAL AHEMDABAD', 'Screen Replacement', NULL, NULL, 'IPHONE 16 PRO MAX', 9999.00, 'Rejected', '2025-02-25 15:42:55'),
(31, 'chirag', 'chirag1234@gmail.com', '28 durganagar silvercity', 'Screen Replacement', '2025-04-24', '20:07:00', 'b  v gf f   fgnngfn', 5000.00, 'Accepted', '2025-04-21 14:33:30'),
(32, 'chirag', 'chirag1234@gmail.com', '28 durganagar silvercity', 'Camera Repair', '2025-04-23', '20:28:00', 'ngfmghmj,,j,j,', 60000.00, 'Accepted', '2025-04-21 14:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `service_reviews`
--

CREATE TABLE `service_reviews` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_reviews`
--

INSERT INTO `service_reviews` (`id`, `service_name`, `user_name`, `rating`, `comment`, `review_date`) VALUES
(1, 'camera repair', 'harsh', 5, 'good service', '2025-02-01 04:12:03'),
(2, 'screen reapir', 'mehul', 4, 'good service but still they can improve', '2025-02-01 04:12:38'),
(3, 'dead pixel repair', 'gayatri', 5, 'i was wooried about my phone but htey fixed it all thanks to nilkanth mobiles', '2025-02-01 04:13:48'),
(7, 'BATTREY REPLACE', 'NIKHIL', 3, 'GOOD!', '2025-02-01 04:43:07');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `stock_id` int(11) NOT NULL,
  `serial_number` varchar(255) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `variant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`stock_id`, `serial_number`, `product_id`, `variant_id`) VALUES
(1, 'SN: 20230715-001-123', NULL, 1),
(2, 'AC-2154-8362', NULL, 2),
(3, 'BC-9702-5628', NULL, 3),
(4, 'SV-7541-0682', NULL, 4),
(5, 'EC-3856-7901', NULL, 5),
(6, 'WR-2108-9746', NULL, 6),
(7, 'SE: 20230715-001-123', NULL, 7),
(8, 'SN: 767556778-001-123', NULL, 1),
(9, 'EC-9702-5628', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `SUPPLIER_ID` int(11) NOT NULL,
  `SUPPLIER_NAME` varchar(50) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `PHONE` varchar(10) NOT NULL,
  `ADDRESS` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`SUPPLIER_ID`, `SUPPLIER_NAME`, `EMAIL`, `PHONE`, `ADDRESS`) VALUES
(127, 'GUPTA SAHIL', 'sahil123@gmail.com', '9928364927', 'SHOP N0-101 SUDARSHAN GADGETS GAYATRI ROAD VIRATNAGAR AHEMDABAD'),
(132, 'RAO KIRTIPAL', 'raokirti@gmail.com', '9037286328', 'A-444,445 RAMJI MOBILES VAISHALINAGAR JAIPUR RAJASTHAN'),
(142, 'SHARMA RONIT', 'ronits@gmail.com', '7723668258', 'SHOP NO-453 GURU MEHER ELECTRONICS BABA DEEPSINGHJI ROAD AMRITSAR PUNJAB'),
(158, 'vishu', 'isabias@gamil.com', '9928364927', '32 karnavati megamall vastral ahmedabad'),
(159, 'Raman', 'ramanbhai123@gmail.com', '9913124353', 'umiya nagar near takshila vastral  ahemdabad');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`BRAND_ID`);

--
-- Indexes for table `cancellations`
--
ALTER TABLE `cancellations`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_cancellation_customer` (`CUSTOMER_ID`),
  ADD KEY `fk_cancellation_service` (`SERVICE_ID`),
  ADD KEY `fk_cancellation_order` (`ORDER_ID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`CART_ID`),
  ADD KEY `fk_customer` (`CUSTOMER_ID`);

--
-- Indexes for table `cart_details`
--
ALTER TABLE `cart_details`
  ADD PRIMARY KEY (`C_ID`),
  ADD KEY `fk_cart_details_cart` (`CART_ID`),
  ADD KEY `fk_cart_details_product` (`PRODUCT_ID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CATEGORY_ID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CUSTOMER_ID`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`);

--
-- Indexes for table `feedback_complaints`
--
ALTER TABLE `feedback_complaints`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_feedback_order` (`ORDER_ID`),
  ADD KEY `fk_feedback_service` (`SERVICE_ID`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `offer`
--
ALTER TABLE `offer`
  ADD PRIMARY KEY (`OFFER_ID`),
  ADD KEY `fk_offer_product` (`PRODUCT_ID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ORDER_ID`),
  ADD KEY `CUSTOMER_ID` (`CUSTOMER_ID`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`ORDER_ITEM_ID`),
  ADD KEY `ORDER_ID` (`ORDER_ID`),
  ADD KEY `PRODUCT_ID` (`PRODUCT_ID`),
  ADD KEY `FOREING KEY` (`VARIANT_ID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`PRODUCT_ID`),
  ADD KEY `fk_product_category` (`CATEGORY_ID`),
  ADD KEY `fk_product_brand` (`BRAND_ID`);

--
-- Indexes for table `product_details`
--
ALTER TABLE `product_details`
  ADD PRIMARY KEY (`PPID`),
  ADD KEY `FOREIGN KEY` (`PRODUCT_ID`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`VARIANT_ID`),
  ADD KEY `PRODUCT_ID` (`PRODUCT_ID`);

--
-- Indexes for table `review_shop`
--
ALTER TABLE `review_shop`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`SERVICE_ID`);

--
-- Indexes for table `service_bookings`
--
ALTER TABLE `service_bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `service_reviews`
--
ALTER TABLE `service_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`SUPPLIER_ID`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cancellations`
--
ALTER TABLE `cancellations`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `CART_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `cart_details`
--
ALTER TABLE `cart_details`
  MODIFY `C_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CATEGORY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CUSTOMER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `feedback_complaints`
--
ALTER TABLE `feedback_complaints`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `offer`
--
ALTER TABLE `offer`
  MODIFY `OFFER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ORDER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `ORDER_ITEM_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `PRODUCT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `product_details`
--
ALTER TABLE `product_details`
  MODIFY `PPID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `VARIANT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `review_shop`
--
ALTER TABLE `review_shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `SERVICE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service_bookings`
--
ALTER TABLE `service_bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `service_reviews`
--
ALTER TABLE `service_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `SUPPLIER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cancellations`
--
ALTER TABLE `cancellations`
  ADD CONSTRAINT `fk_cancellation_customer` FOREIGN KEY (`CUSTOMER_ID`) REFERENCES `customer` (`CUSTOMER_ID`),
  ADD CONSTRAINT `fk_cancellation_order` FOREIGN KEY (`ORDER_ID`) REFERENCES `orders` (`ORDER_ID`),
  ADD CONSTRAINT `fk_cancellation_service` FOREIGN KEY (`SERVICE_ID`) REFERENCES `service_bookings` (`booking_id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`CUSTOMER_ID`) REFERENCES `customer` (`CUSTOMER_ID`);

--
-- Constraints for table `cart_details`
--
ALTER TABLE `cart_details`
  ADD CONSTRAINT `fk_cart_details_cart` FOREIGN KEY (`CART_ID`) REFERENCES `cart` (`CART_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_details_product` FOREIGN KEY (`PRODUCT_ID`) REFERENCES `product` (`PRODUCT_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `offer`
--
ALTER TABLE `offer`
  ADD CONSTRAINT `fk_offer_product` FOREIGN KEY (`PRODUCT_ID`) REFERENCES `product` (`PRODUCT_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`CUSTOMER_ID`) REFERENCES `customer` (`CUSTOMER_ID`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `FOREING KEY` FOREIGN KEY (`VARIANT_ID`) REFERENCES `product_variants` (`VARIANT_ID`),
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`ORDER_ID`) REFERENCES `orders` (`ORDER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`PRODUCT_ID`) REFERENCES `product` (`PRODUCT_ID`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_brand` FOREIGN KEY (`BRAND_ID`) REFERENCES `brand` (`BRAND_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`CATEGORY_ID`) REFERENCES `categories` (`CATEGORY_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `product_details`
--
ALTER TABLE `product_details`
  ADD CONSTRAINT `FOREIGN KEY` FOREIGN KEY (`PRODUCT_ID`) REFERENCES `product` (`PRODUCT_ID`);

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`PRODUCT_ID`) REFERENCES `product` (`PRODUCT_ID`) ON DELETE CASCADE;

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`PRODUCT_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`VARIANT_ID`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
