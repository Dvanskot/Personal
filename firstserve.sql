-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2014 at 04:41 PM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `firstserve`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE IF NOT EXISTS `books` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_name` varchar(50) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `book_price` double NOT NULL,
  `book_isbn` varchar(50) NOT NULL,
  `book_author` varchar(50) NOT NULL,
  `book_theme` varchar(50) NOT NULL,
  `book_date` varchar(40) NOT NULL,
  `book_filename` varchar(50) NOT NULL,
  `book_icon` varchar(50) NOT NULL,
  `book_release` varchar(40) NOT NULL,
  `book_purchase` int(11) NOT NULL,
  PRIMARY KEY (`book_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `book_name`, `cat_id`, `book_price`, `book_isbn`, `book_author`, `book_theme`, `book_date`, `book_filename`, `book_icon`, `book_release`, `book_purchase`) VALUES
(1, 'PHP5 and MYSQL Bible', 2, 460, '1943-4343-3453-3n35', 'KG', 'Internet Programming', '2014-05-07', 'php.pdf', 'php.jpg', '2012', 0),
(3, 'Java Programming', 1, 120, '232-3434-3434-3443', 'Joyce Farrel', 'Programming', '2014-05-08T20:19:27+02:00', 'Java+Programming+-+Joyce+Farrel.pdf', 'java.jpg', '2011', 0),
(4, 'The Official Ubuntu Book', 4, 578.99, '01-330-17-N605', 'Matthew Helmke,  Amber Graner,  Kyle Rankin', 'Computers', '2014-05-06', 'The Official Ubuntu Book, 7th Edition.pdf', 'ubuntu.jpg', '2010', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `cust_id` varchar(255) NOT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(50) NOT NULL,
  `cat_desc` varchar(250) NOT NULL,
  `cat_date` varchar(40) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_name`, `cat_desc`, `cat_date`) VALUES
(1, 'Education', 'Educational books for use by Tertiary instituations.', '2014-05-02'),
(2, 'Knowledge', 'This category provides books for every sphere of life.', '2014-05-05');

-- --------------------------------------------------------

--
-- Table structure for table `download`
--

CREATE TABLE IF NOT EXISTS `download` (
  `down_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id` varchar(255) NOT NULL,
  `down_code` bigint(20) NOT NULL,
  `book_filename` varchar(50) NOT NULL,
  `down_date` varchar(40) NOT NULL,
  `down_number` int(11) NOT NULL,
  `trans_id` int(11) NOT NULL,
  PRIMARY KEY (`down_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `prod_id` int(11) NOT NULL,
  `prod_name` varchar(100) NOT NULL,
  `prod_author` varchar(50) NOT NULL,
  `prod_price` float NOT NULL,
  `prod_image` varchar(50) NOT NULL,
  `prod_link` varchar(50) NOT NULL,
  `prod_isbn` varchar(50) NOT NULL,
  `prod_desc` varchar(1000) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `prod_publisher` varchar(50) NOT NULL,
  `prod_year` varchar(4) NOT NULL,
  PRIMARY KEY (`prod_id`,`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE IF NOT EXISTS `transaction` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id` varchar(255) NOT NULL,
  `trans_numbooks` int(11) NOT NULL,
  `trans_date` varchar(30) NOT NULL,
  PRIMARY KEY (`trans_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(40) NOT NULL,
  `country` varchar(50) NOT NULL,
  `act_code` int(11) NOT NULL,
  `is_active` int(11) NOT NULL,
  `is_ban` int(11) NOT NULL,
  `is_admin` int(11) NOT NULL,
  `validation` varchar(40) NOT NULL,
  `date_created` varchar(25) NOT NULL,
  `date_lastlogin` varchar(25) NOT NULL,
  `cellnum` text,
  `postalAddr` varchar(50) DEFAULT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `email`, `password`, `country`, `act_code`, `is_active`, `is_ban`, `is_admin`, `validation`, `date_created`, `date_lastlogin`, `cellnum`, `postalAddr`, `fullname`) VALUES
('dakalo@gmail.com', 'dakalo@gmail.com', '71e8afeb3f980ecc4d4b9d886e3a543c', 'South Africa', 7960, 1, 0, 0, '0451bfaf87ea00b20588bfadc69e8323', '2013-05-07T19:52:59+02:00', '2013-05-07T19:52:59+02:00', '0793607129', 'P.O.Box 23\r\nSibasa\r\n0970', 'Dakalo Nemaangnai'),
('kg@gmail.com', 'kg@gmail.com', 'KG123t', 'South Africa', 1962, 1, 0, 0, '0451bfaf87ea00b20588bfadc69e8323', '2014-05-03', '', '0797877838', '124 The Ochards, Pretoria North 0052', 'Thangoane Katlego');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
