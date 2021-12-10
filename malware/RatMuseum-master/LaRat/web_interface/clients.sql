-- Use this to create the tables needed for LaRat

SET NAMES utf8;
SET foreign_key_checks = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Table structure for table `clients`
--
CREATE TABLE IF NOT EXISTS `clients` (
  `objectId` varchar(10) CHARACTER SET utf8 NOT NULL,
  `carrier` varchar(15) CHARACTER SET utf8 NOT NULL,
  `phoneNumber` varchar(10) CHARACTER SET utf8 NOT NULL,
  `deviceid` varchar(25) CHARACTER SET utf8 NOT NULL,
  `sdkversion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_messages`
--
CREATE TABLE IF NOT EXISTS `client_messages` (
  `objectId` varchar(50) CHARACTER SET utf8 NOT NULL,
  `message_type` varchar(50) NOT NULL,
  `message` mediumtext CHARACTER SET utf8 NOT NULL,
  `unread` tinyint(1) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `location_history`
--
CREATE TABLE IF NOT EXISTS `location_history` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `objectId` varchar(10) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `clients`
  ADD UNIQUE KEY `objectId` (`objectId`);
