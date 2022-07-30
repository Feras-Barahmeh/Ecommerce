-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2022 at 02:33 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL COMMENT 'Identifier category',
  `name` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL,
  `description` text NOT NULL,
  `dataAdd` date NOT NULL,
  `ordering` int(11) NOT NULL,
  `visibility` tinyint(4) NOT NULL DEFAULT 0,
  `allowComment` tinyint(4) NOT NULL DEFAULT 0,
  `allowAdvertisement` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `name`, `parent`, `description`, `dataAdd`, `ordering`, `visibility`, `allowComment`, `allowAdvertisement`) VALUES
(1, 'PC', 0, 'All you need to you comomputer', '2022-07-17', 2, 1, 1, 1),
(2, 'Clothes', 0, ' Fashion In 2022', '2022-07-17', 5, 0, 0, 0),
(3, 'PlayStaions', 0, 'play diffrent games ', '2022-07-17', 6, 0, 0, 0),
(4, 'Electronics', 0, 'electronics devices', '2022-07-18', 3, 0, 0, 0),
(5, 'Books', 0, 'This category has book for difference felids ', '2022-07-18', 4, 0, 0, 0),
(6, 'Cell Phones', 0, 'this category has nice and differance phone', '2022-07-18', 3, 0, 0, 0),
(9, 'Fiva 2023', 3, 'all fetcher fotball geme in 2022', '2022-07-19', 4, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `commID` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `commDate` date NOT NULL,
  `commItemID` int(11) NOT NULL,
  `commUserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`commID`, `comment`, `status`, `commDate`, `commItemID`, `commUserID`) VALUES
(10, 'Comfortable for the back ?? ', 0, '2022-07-29', 20, 2);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `itemID` int(11) NOT NULL,
  `nameItem` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `dateAdd` date NOT NULL,
  `madeIn` varchar(255) NOT NULL,
  `tage` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `pictureItem` varchar(255) NOT NULL,
  `rating` smallint(6) NOT NULL,
  `approve` tinyint(11) NOT NULL DEFAULT 0,
  `catID` int(11) NOT NULL,
  `memberID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`itemID`, `nameItem`, `description`, `price`, `dateAdd`, `madeIn`, `tage`, `image`, `status`, `pictureItem`, `rating`, `approve`, `catID`, `memberID`) VALUES
(1, 'Moues', 'Nice moues for gaming', '$10', '2022-07-17', 'china', '', '', '0', 'Moues_61ci8Y1eVJL._AC_SL1500_.jpg', 0, 1, 1, 7),
(2, 'Boy Friend', 'fabric turke', '30', '2022-07-17', 'Turkey', 'For free to Azrqa, discount 50% for amman', '', '1', '', 0, 1, 2, 5),
(4, 'versatche', '2002 model', '10', '2022-07-17', 'chain', '', '', '1', '', 0, 1, 2, 2),
(5, 'keybord', 'blank color, mechanecal', '30', '2022-07-17', 'chain', 'free', '', '1', '', 0, 1, 1, 2),
(6, 'Fiva 2014', 'Fiva, bese', '30', '2022-07-17', 'America', '', '', '1', 'Fiva 2014_images.jpg', 0, 1, 3, 7),
(7, 'PAVILION', 'My fighter', '1000', '2022-07-17', 'America', 'free', '', '1', '', 0, 1, 1, 2),
(8, 'joyStick', 'from sony', '30', '2022-07-17', 'chain', '', '', '1', '', 0, 1, 1, 2),
(10, 'Chemistry', 'Tawjwhe Book', '12', '2022-07-18', 'jordan', '', '', '1', '', 0, 1, 5, 4),
(11, 'HUAWEI Y9s', 'Nice product from huawei this phone has ram 8g and 128G hard desci', '$300', '2022-07-19', 'chain', 'supported', '', '1', 'HUAWEI Y9s_download (4).jpg', 0, 1, 6, 7),
(12, 'Math Book', 'tawjwhe book from Jordan', '10', '2022-07-19', 'jordan', 'Discount 5%, free for zarqa student., Discount 75% from amman student', '', '1', '', 0, 1, 5, 4),
(13, 'HeadPhone ', 'Nice to leasen music ', '10', '2022-07-19', 'chain', 'Tags Item', '', '1', '', 0, 1, 6, 2),
(14, 'Shorts', 'to Summer', '10', '2022-07-19', 'chain', 'free', '', '1', '', 0, 1, 2, 5),
(16, 'Bag', 'hand Bag', '35', '2022-07-26', 'chain', 'discount', '', '1', 'Bag_bag.jpg', 0, 1, 2, 2),
(19, 'Skirt', 'To giles', '100', '2022-07-26', 'America', 'supported', '', '1', '_download(2).jpg', 0, 1, 2, 3),
(20, 'RESPAWN 110 Racing Style Gaming Chair, ', 'RESPAWN 110 Racing Style Gaming Chair, Reclining Ergonomic Chair with Footrest, in Red', '156', '2022-07-26', 'America', 'Discount 5%', '', '1', 'RESPAWN 110 Racing Style Gaming Chair, _61O4ilN5v1S._AC_SL1500_.jpg', 0, 1, 1, 7),
(21, 'PS5 Console- Horizon Forbidden West Bundle', 'Faster Loading: Fast-travel across the map and get back into the game almost instantly with the PlayStation5 console’s ultra-high-speed SSD and fast load times.', '550', '2022-07-26', 'America', 'supported', '', '1', 'PS5 Console- Horizon Forbidden West Bundle_ps5.jpg', 0, 1, 3, 5),
(22, 'SKULL AND BONES – PlayStation 5, Standard Edition', 'Launch Edition includes in-game downloadable content Kratos Risen Snow Armor & Atreus Risen Snow Tunic (Cosmetic)', '70', '2022-07-26', 'japan', '', '', '1', 'SKULL AND BONES – PlayStation 5, Standard Edition_download(2).jpg', 0, 1, 3, 5),
(23, 'Printer', 'Print papers', '223', '2022-07-26', 'chain', '', '', '1', 'Printer_download (2).jpg', 0, 1, 1, 11),
(24, 'Buttere', 'charge laptop', '30', '2022-07-26', 'chain', 'Tags Item', '', '1', 'Buttere_download (3).jpg', 0, 1, 4, 2),
(25, 'Xbox Series S', 'Go all digital with Xbox Series S and enjoy next-gen performance in the smallest Xbox ever, at a great price. Bundle includes: Xbox Series S console, one Xbox Wireless Controller, a high-speed HDMI cable, power cable, and 2 AA batteries.', '28500', '2022-07-27', 'America', 'Tags Item', '', '1', 'Xbox Series S_71NBQ2a52CL._SL1500_.jpg', 0, 1, 3, 2),
(26, 'laptop', 'to finch desktop tasks', '$1000', '2022-07-29', 'chain', '', '', '1', 'laptop_photo-1618424181497-157f25b6ddd5.jpg', 0, 1, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL COMMENT 'Identefier user',
  `userName` varchar(255) NOT NULL COMMENT 'the name can user enter webside',
  `password` varchar(255) CHARACTER SET utf8mb4 NOT NULL COMMENT 'password account user',
  `email` varchar(255) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `preDate` date NOT NULL,
  `groubID` int(11) NOT NULL DEFAULT 0,
  `trustStatus` int(11) NOT NULL DEFAULT 0,
  `regStatus` int(11) NOT NULL DEFAULT 0,
  `profilePicture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `userName`, `password`, `email`, `fullName`, `preDate`, `groubID`, `trustStatus`, `regStatus`, `profilePicture`) VALUES
(1, 'feras', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'feasfadi345@gmail.com', 'Jenan Fadi Barahmeh', '2022-07-17', 1, 0, 1, 'feras_logo1.jpg'),
(2, 'majd', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'majd@majd.com', 'Jenan Fadi Barahmeh', '2022-07-17', 0, 0, 1, 'majd_download (1).jpg'),
(3, 'jenan', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'j@j.com', 'Jenan Fadi Barahmeh', '2022-07-17', 0, 0, 1, 'jenan_avatar-icon-of-girl-in-a-baseball-cap-vector-16225068.jpg'),
(4, 'khaled', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'k@k.com', 'Jenan Fadi Barahmeh', '2022-07-18', 0, 0, 0, 'khaled_download (1).jpg'),
(5, 'fadi', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'fadi@f.com', 'Jenan Fadi Barahmeh', '2022-07-18', 0, 0, 0, 'fadi_download.jpg'),
(6, 'mohammad', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'mohamd@jmail.com', 'Jenan Fadi Barahmeh', '2022-07-18', 0, 0, 0, 'mohammad_download (5).jpg'),
(7, 'ahmad', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'ahmad@gmail.com', 'Jenan Fadi Barahmeh', '2022-07-18', 0, 0, 0, ''),
(8, 'hussam', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'husam@gmail.com', 'Jenan Fadi Barahmeh', '2022-07-18', 0, 0, 0, ''),
(10, 'belalfadi', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'b@b.com', 'Jenan Fadi Barahmeh', '2022-07-20', 0, 0, 0, ''),
(11, 'ronaldo', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'ronaldo@cr7.com', 'Jenan Fadi Barahmeh', '2022-07-21', 0, 0, 1, ''),
(12, 'saqer', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 's@s.com', 'Jenan Fadi Barahmeh', '2022-07-26', 0, 0, 1, ''),
(13, 'omar', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'omar@omar.com', 'Jenan Fadi Barahmeh', '2022-07-26', 0, 0, 0, ''),
(14, 'pope', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'pop@po.com', 'Jenan Fadi Barahmeh', '2022-07-26', 0, 0, 0, 'pope_download (6).jpg'),
(15, 'Bahaa Sultan', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'b@b.com', 'Jenan Fadi Barahmeh', '2022-07-27', 0, 0, 0, ''),
(16, 'ehaptawfik', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'eh@eh.com', 'Jenan Fadi Barahmeh', '2022-07-27', 0, 0, 0, ''),
(17, 'amrdiabe', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'a@a.com', 'Jenan Fadi Barahmeh', '2022-07-27', 0, 0, 0, ''),
(18, 'amirmuneeb', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'amir@a.com', 'Jenan Fadi Barahmeh', '2022-07-27', 0, 0, 0, ''),
(19, 'saleh', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'saleh@m.com', 'Saleh Mustafa', '2022-07-28', 3, 0, 1, 'saleh_download.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commID`),
  ADD KEY `commItem` (`commItemID`),
  ADD KEY `commUser` (`commUserID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`itemID`),
  ADD UNIQUE KEY `nameItem` (`nameItem`),
  ADD KEY `categorieName` (`catID`),
  ADD KEY `memberName` (`memberID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `userName` (`userName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifier category', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `itemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identefier user', AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `commItem` FOREIGN KEY (`commItemID`) REFERENCES `items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commUser` FOREIGN KEY (`commUserID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `categorieName` FOREIGN KEY (`catID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `memberName` FOREIGN KEY (`memberID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
