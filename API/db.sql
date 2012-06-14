-- phpMyAdmin SQL Dump
-- version 3.3.10.4
-- http://www.phpmyadmin.net
--
-- Host: mysql.farmtab.com
-- Generation Time: Jun 13, 2012 at 07:41 PM
-- Server version: 5.1.39
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `farmtab`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_clients`
--

CREATE TABLE IF NOT EXISTS `api_clients` (
  `client_name` varchar(255) NOT NULL,
  `api_key` varchar(64) NOT NULL,
  PRIMARY KEY (`api_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 NOT NULL,
  `pin` varchar(48) CHARACTER SET latin1 NOT NULL,
  `salt` varchar(15) CHARACTER SET latin1 NOT NULL,
  `phone` varchar(255) CHARACTER SET latin1 NOT NULL,
  `fb_id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `fb_token` varchar(64) CHARACTER SET latin1 NOT NULL,
  `twitter_id` varchar(24) CHARACTER SET latin1 NOT NULL,
  `twitter_token` varchar(64) CHARACTER SET latin1 NOT NULL,
  `fsq_id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `fsq_token` varchar(64) CHARACTER SET latin1 NOT NULL,
  `img_url` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `customer_x_tab`
--

CREATE TABLE IF NOT EXISTS `customer_x_tab` (
  `customer_id` int(11) NOT NULL,
  `tab_id` int(11) NOT NULL,
  PRIMARY KEY (`customer_id`,`tab_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_x_transaction`
--

CREATE TABLE IF NOT EXISTS `customer_x_transaction` (
  `customer_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  PRIMARY KEY (`customer_id`,`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `farms`
--

CREATE TABLE IF NOT EXISTS `farms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `pass` varchar(48) NOT NULL,
  `salt` varchar(15) NOT NULL,
  `pin` varchar(255) NOT NULL,
  `farm_name` varchar(255) NOT NULL,
  `farm_address` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `schedule` int(11) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `long` float(10,6) NOT NULL,
  `date_joined` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `farm_x_customer`
--

CREATE TABLE IF NOT EXISTS `farm_x_customer` (
  `farm_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  PRIMARY KEY (`farm_id`,`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `farm_x_inventory`
--

CREATE TABLE IF NOT EXISTS `farm_x_inventory` (
  `farm_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  PRIMARY KEY (`farm_id`,`inventory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `farm_x_transaction`
--

CREATE TABLE IF NOT EXISTS `farm_x_transaction` (
  `farm_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  PRIMARY KEY (`farm_id`,`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `farm_x_venue`
--

CREATE TABLE IF NOT EXISTS `farm_x_venue` (
  `farm_id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  PRIMARY KEY (`farm_id`,`venue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

CREATE TABLE IF NOT EXISTS `inventories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `farm_id` varchar(255) NOT NULL,
  `stock` varchar(255) NOT NULL,
  `availability` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_x_items`
--

CREATE TABLE IF NOT EXISTS `inventory_x_items` (
  `inventory_id` int(11) NOT NULL,
  `items_id` int(11) NOT NULL,
  PRIMARY KEY (`inventory_id`,`items_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_x_venue`
--

CREATE TABLE IF NOT EXISTS `inventory_x_venue` (
  `inventory_id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  PRIMARY KEY (`inventory_id`,`venue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `photo` int(11) NOT NULL,
  `ppu` varchar(255) NOT NULL,
  `unit_size` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(256) NOT NULL,
  `request_user_agent` varchar(512) NOT NULL,
  `request_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `request_ip` varchar(64) NOT NULL,
  `login_successful` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `user` int(11) NOT NULL,
  `farm` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE IF NOT EXISTS `schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `M` varchar(255) NOT NULL,
  `T` varchar(255) NOT NULL,
  `W` varchar(255) NOT NULL,
  `Th` varchar(255) NOT NULL,
  `F` varchar(255) NOT NULL,
  `Sa` varchar(255) NOT NULL,
  `Su` varchar(255) NOT NULL,
  `H` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `tabs`
--

CREATE TABLE IF NOT EXISTS `tabs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `farm_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`farm_id`,`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receipt_dump` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `farm_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE IF NOT EXISTS `venues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venue_name` varchar(255) NOT NULL,
  `venue_address` varchar(255) NOT NULL,
  `schedule` varchar(255) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `long` float(10,6) NOT NULL,
  `social` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
