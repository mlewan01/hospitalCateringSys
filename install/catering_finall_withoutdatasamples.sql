-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2018 at 05:56 PM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `catering`
--

-- --------------------------------------------------------

--
-- Table structure for table `beds`
--

CREATE TABLE `beds` (
  `b_id` int(10) UNSIGNED NOT NULL,
  `b_name` varchar(20) COLLATE utf8_bin NOT NULL,
  `b_id_ward` int(10) UNSIGNED NOT NULL COMMENT 'location in which ward',
  `b_id_hospital` int(10) UNSIGNED NOT NULL COMMENT 'location in which hospital',
  `b_phone` varchar(15) COLLATE utf8_bin NOT NULL,
  `b_occupied` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `h_id` int(10) UNSIGNED NOT NULL,
  `h_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `h_address` varchar(500) COLLATE utf8_bin NOT NULL,
  `h_email` varchar(30) COLLATE utf8_bin NOT NULL,
  `h_phone` varchar(15) COLLATE utf8_bin NOT NULL,
  `h_description` varchar(500) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `i_id` int(10) UNSIGNED NOT NULL COMMENT 'unique id of item',
  `i_name` varchar(100) COLLATE utf8_bin NOT NULL COMMENT 'name of the item',
  `i_ingredients` varchar(1000) COLLATE utf8_bin NOT NULL COMMENT 'list of ingredients ',
  `i_texture` varchar(500) COLLATE utf8_bin NOT NULL COMMENT 'texture of the item',
  `i_colour` varchar(500) COLLATE utf8_bin NOT NULL COMMENT 'colour of the item',
  `i_method` varchar(2000) COLLATE utf8_bin NOT NULL COMMENT 'method of preparation',
  `i_nutrition` varchar(800) COLLATE utf8_bin NOT NULL COMMENT 'nutritional values',
  `i_reference` varchar(100) COLLATE utf8_bin NOT NULL COMMENT 'main ingredient and dish type',
  `i_image` varchar(200) COLLATE utf8_bin NOT NULL COMMENT 'an image',
  `i_flavour` varchar(300) COLLATE utf8_bin NOT NULL COMMENT 'flavour description',
  `i_allergens` varchar(100) COLLATE utf8_bin NOT NULL COMMENT 'allrgens in the item'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `l_id` int(10) UNSIGNED NOT NULL COMMENT 'loggs id',
  `l_msg` varchar(250) COLLATE utf8_bin NOT NULL COMMENT 'message',
  `l_sql` varchar(250) COLLATE utf8_bin NOT NULL COMMENT 'sql acompaning the message if available',
  `l_date` int(10) NOT NULL COMMENT 'date of the evet',
  `l_id_staff` int(10) UNSIGNED NOT NULL COMMENT 'logged in stuff id related to the event',
  `l_type` int(4) NOT NULL COMMENT 'type of event'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `m_id` int(10) UNSIGNED NOT NULL,
  `m_name` varchar(30) COLLATE utf8_bin NOT NULL,
  `m_sequence` tinyint(3) UNSIGNED NOT NULL,
  `m_id_menuset` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `mi_id` int(10) UNSIGNED NOT NULL,
  `mi_id_product` int(10) UNSIGNED NOT NULL,
  `mi_id_menu` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `menu_sets`
--

CREATE TABLE `menu_sets` (
  `ms_id` int(10) UNSIGNED NOT NULL COMMENT 'menu_set id',
  `ms_name` varchar(20) COLLATE utf8_bin NOT NULL COMMENT 'Breakfast, Lunch, Supper',
  `ms_description` varchar(500) COLLATE utf8_bin NOT NULL COMMENT 'description of the menu_set',
  `ms_date_from` int(10) NOT NULL COMMENT 'date in Unix format from which to start the menu rotation',
  `ms_length` tinyint(3) UNSIGNED NOT NULL COMMENT 'lenght in days, for menues to be displayed before rotating back to the first menu in the sequence',
  `ms_type` varchar(20) COLLATE utf8_bin NOT NULL COMMENT 'nhs or private',
  `ms_diet` varchar(20) COLLATE utf8_bin NOT NULL COMMENT 'standard, vegetarian, vegan, halal, kosher',
  `ms_nutrition` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '	water, clear fluids, free fluids, soft diet, light diet, eating & drinking'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `o_id` int(10) UNSIGNED NOT NULL COMMENT 'unique id of the order entry',
  `o_id_patient` int(10) UNSIGNED NOT NULL COMMENT 'forein key from the patients table',
  `o_id_item` int(10) UNSIGNED NOT NULL COMMENT 'forein key from the  item table',
  `o_id_bed` int(10) UNSIGNED NOT NULL COMMENT 'forein key from the bed table',
  `o_date_meal` int(10) UNSIGNED NOT NULL COMMENT 'date of the meal',
  `o_date` int(10) UNSIGNED NOT NULL COMMENT 'date of the order',
  `o_meal` varchar(10) COLLATE utf8_bin NOT NULL COMMENT 'time of the meal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `p_id` int(10) UNSIGNED NOT NULL,
  `p_number` int(10) UNSIGNED NOT NULL,
  `p_name` varchar(20) COLLATE utf8_bin NOT NULL,
  `p_title` tinytext COLLATE utf8_bin NOT NULL,
  `p_phone` varchar(15) COLLATE utf8_bin NOT NULL,
  `p_email` varchar(30) COLLATE utf8_bin NOT NULL,
  `p_info` varchar(500) COLLATE utf8_bin NOT NULL,
  `p_type` varchar(20) COLLATE utf8_bin NOT NULL COMMENT 'nhs, private',
  `p_diet` varchar(20) COLLATE utf8_bin NOT NULL COMMENT 'standard, vegetarian, vegan, halal, kosher',
  `p_nutrition` varchar(20) COLLATE utf8_bin NOT NULL COMMENT 'water, clear fluids, free fluids, soft diet, light diet, eating & drinking',
  `p_allergies` varchar(250) COLLATE utf8_bin NOT NULL,
  `p_regdate` int(10) UNSIGNED NOT NULL,
  `p_active` int(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 active, 0 non active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `pat_bed`
--

CREATE TABLE `pat_bed` (
  `pb_id` int(10) UNSIGNED NOT NULL,
  `pb_id_bed` int(10) UNSIGNED NOT NULL,
  `pb_id_patient` int(10) UNSIGNED NOT NULL,
  `pb_date_from` int(10) UNSIGNED NOT NULL,
  `pb_date_to` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `pat_diet`
--

CREATE TABLE `pat_diet` (
  `pd_id` int(10) UNSIGNED NOT NULL,
  `pd_id_patient` int(10) UNSIGNED NOT NULL,
  `pd_date` int(10) UNSIGNED NOT NULL,
  `pd_type` varchar(20) COLLATE utf8_bin NOT NULL,
  `pd_diet` varchar(20) COLLATE utf8_bin NOT NULL,
  `pd_nutrition` varchar(20) COLLATE utf8_bin NOT NULL,
  `pd_allergies` varchar(250) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `u_id` int(10) UNSIGNED NOT NULL,
  `u_name` varchar(20) COLLATE utf8_bin NOT NULL,
  `u_username` varchar(30) COLLATE utf8_bin NOT NULL,
  `u_password` varchar(128) COLLATE utf8_bin NOT NULL,
  `u_privileges` varchar(20) COLLATE utf8_bin NOT NULL,
  `u_department` varchar(30) COLLATE utf8_bin NOT NULL,
  `u_role` varchar(30) COLLATE utf8_bin NOT NULL,
  `u_phone` varchar(15) COLLATE utf8_bin NOT NULL,
  `u_email` varchar(30) COLLATE utf8_bin NOT NULL,
  `u_regdate` int(10) UNSIGNED NOT NULL,
  `u_info` varchar(250) COLLATE utf8_bin NOT NULL,
  `u_active` tinyint(1) NOT NULL DEFAULT '0',
  `u_passrec` varchar(32) COLLATE utf8_bin NOT NULL COMMENT 'unic token for password recovery. Owner can Identify himself as legitimage owner of the account and reset password'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='users of the catering system';

-- --------------------------------------------------------

--
-- Table structure for table `valus`
--

CREATE TABLE `valus` (
  `v_id` int(10) UNSIGNED NOT NULL,
  `v_value` varchar(50) COLLATE utf8_bin NOT NULL,
  `v_type` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `wards`
--

CREATE TABLE `wards` (
  `w_id` int(10) UNSIGNED NOT NULL,
  `w_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `w_id_hospital` int(10) UNSIGNED NOT NULL COMMENT 'located by hospital id',
  `w_email` varchar(30) COLLATE utf8_bin NOT NULL,
  `w_phone` varchar(15) COLLATE utf8_bin NOT NULL,
  `w_description` varchar(500) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `beds`
--
ALTER TABLE `beds`
  ADD PRIMARY KEY (`b_id`),
  ADD KEY `b_id_ward` (`b_id_ward`),
  ADD KEY `b_id_hospital` (`b_id_hospital`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`h_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`i_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`l_id`),
  ADD KEY `l_sql` (`l_sql`),
  ADD KEY `l_id_staff` (`l_id_staff`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`m_id`),
  ADD KEY `m_id_menuset` (`m_id_menuset`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`mi_id`),
  ADD KEY `mi_id_product` (`mi_id_product`),
  ADD KEY `mi_id_menu` (`mi_id_menu`);

--
-- Indexes for table `menu_sets`
--
ALTER TABLE `menu_sets`
  ADD PRIMARY KEY (`ms_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`o_id`),
  ADD KEY `o_id_patient` (`o_id_patient`),
  ADD KEY `o_id_item` (`o_id_item`),
  ADD KEY `o_id_bed` (`o_id_bed`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`p_id`);

--
-- Indexes for table `pat_bed`
--
ALTER TABLE `pat_bed`
  ADD PRIMARY KEY (`pb_id`),
  ADD KEY `pb_id_bed` (`pb_id_bed`),
  ADD KEY `pb_id_patient` (`pb_id_patient`);

--
-- Indexes for table `pat_diet`
--
ALTER TABLE `pat_diet`
  ADD PRIMARY KEY (`pd_id`),
  ADD KEY `pd_id_patient` (`pd_id_patient`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`);

--
-- Indexes for table `valus`
--
ALTER TABLE `valus`
  ADD PRIMARY KEY (`v_id`);

--
-- Indexes for table `wards`
--
ALTER TABLE `wards`
  ADD PRIMARY KEY (`w_id`),
  ADD KEY `w_id_hospital` (`w_id_hospital`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `beds`
--
ALTER TABLE `beds`
  MODIFY `b_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `h_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `i_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'unique id of item', AUTO_INCREMENT=217;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `l_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'loggs id', AUTO_INCREMENT=1625;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `m_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `mi_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `menu_sets`
--
ALTER TABLE `menu_sets`
  MODIFY `ms_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'menu_set id', AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `o_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'unique id of the order entry', AUTO_INCREMENT=430;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `p_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pat_bed`
--
ALTER TABLE `pat_bed`
  MODIFY `pb_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `pat_diet`
--
ALTER TABLE `pat_diet`
  MODIFY `pd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `valus`
--
ALTER TABLE `valus`
  MODIFY `v_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=315;

--
-- AUTO_INCREMENT for table `wards`
--
ALTER TABLE `wards`
  MODIFY `w_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `beds`
--
ALTER TABLE `beds`
  ADD CONSTRAINT `beds_ibfk_1` FOREIGN KEY (`b_id_ward`) REFERENCES `wards` (`w_id`);

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`l_id_staff`) REFERENCES `users` (`u_id`) ON UPDATE CASCADE;

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`m_id_menuset`) REFERENCES `menu_sets` (`ms_id`) ON UPDATE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`mi_id_product`) REFERENCES `items` (`i_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `menu_items_ibfk_2` FOREIGN KEY (`mi_id_menu`) REFERENCES `menus` (`m_id`) ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`o_id_patient`) REFERENCES `patients` (`p_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`o_id_item`) REFERENCES `items` (`i_id`) ON UPDATE CASCADE;

--
-- Constraints for table `pat_bed`
--
ALTER TABLE `pat_bed`
  ADD CONSTRAINT `pat_bed_ibfk_1` FOREIGN KEY (`pb_id_bed`) REFERENCES `beds` (`b_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pat_bed_ibfk_2` FOREIGN KEY (`pb_id_patient`) REFERENCES `patients` (`p_id`) ON UPDATE CASCADE;

--
-- Constraints for table `pat_diet`
--
ALTER TABLE `pat_diet`
  ADD CONSTRAINT `pat_diet_ibfk_1` FOREIGN KEY (`pd_id_patient`) REFERENCES `patients` (`p_id`) ON UPDATE CASCADE;

--
-- Constraints for table `wards`
--
ALTER TABLE `wards`
  ADD CONSTRAINT `wards_ibfk_1` FOREIGN KEY (`w_id_hospital`) REFERENCES `hospitals` (`h_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
