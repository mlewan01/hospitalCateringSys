-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 20, 2018 at 05:26 PM
-- Server version: 5.6.39
-- PHP Version: 5.6.33

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
-- Dumping data for table `users`
--

INSERT INTO `users` (`u_id`, `u_name`, `u_username`, `u_password`, `u_privileges`, `u_department`, `u_role`, `u_phone`, `u_email`, `u_regdate`, `u_info`, `u_active`, `u_passrec`) VALUES
(18, 'level1', 'level1', 'a6e0834b7b2b7bd81e813bfee2125b9b68b228d22b65c4feb55dc388f41d38c69254e91a5815573ff1cd0160b15e8d4d1d8e158252c68b1c9c64ffd7c54f7d5c', '1', 'Catering', '1', '1111-1111', 'level1@mail.com', 1520543799, '', 1, ''),
(19, 'level2', 'level2', '83f71715829332cfcf238c805ddc2a3910bed5bd21ed69ab1eff47955fdc4fe0b0e69dd7bf93dbd2517e712d05bb76cdded9866fade0e72efb7af6038493806e', '2', 'Medical', 'Nurse', '222-2222', 'level2@mail.com', 1520543799, '', 1, ''),
(20, 'level3', 'level3', '06ed1673844dd07d224a9580e0c331505d2a7003edab9bb5b31c67e3c31a378c29e02a1fdd1b612d6e4125ffb2061ae83675593caec2f92ae9a0a1f0976cbc10', '3', 'Dietetic', 'Dietician', '333-3333', 'level3@mail.com', 1520543799, '', 1, ''),
(21, 'level4', 'level4', '5c3af5ef52aab57bcbacbf72fcd6c617b7397393fc46b23f69f777acfff0006e2b4913024acfa34a0646687eafc730e90ceb583d8d2dd20dcf78e4b5e4920b2f', '4', 'Facilities', 'Administration', '444-4444', 'level4@mail.com', 1520543799, '', 1, ''),
(22, 'level5', 'level5', '568f26e1aae11ccbb0bb0e368c3cf49b94055d51b8f6d65a55579126c4ac41fb26f96368e599e8b05da16bed623d8334e8e96a96f968c10b230c50c93ce61937', '5', 'Catering', 'Chef', '555-5555', 'level5@email.com', 1520543799, '', 1, ''),
(23, 'level6', 'level6', '305fa9e4aab26f1636f6af0b42ee064e058d1b6290bfe9b628e650983a6f26b84c46007754f33f04f06a9baae7ca42657890a545f3f24faaa4e7c3ab8a7c6954', '6', 'Catering', 'Manager', '666-6666', 'level6@mail.com', 1520543799, '', 1, ''),
(24, 'George', 'george', '9f3e01a03e75524f7e0400fdf9961ec0c6d75dc942b2f76efb66d019d3f8a1f236f2d747180fcb614bbf19a1ad5c24bd92443497f6a72645f0ea745b85d4d058', '10', 'Computer Science', 'Professor', '1010-1010', 'gmagoulas@dcs.bbk.ac.uk', 1520543799, '', 1, '');

