/*
SQLyog Ultimate v8.55 
MySQL - 5.7.9 : Database - noderatio
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`noderatio` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `noderatio`;

/*Table structure for table `t_admins` */

DROP TABLE IF EXISTS `t_admins`;

CREATE TABLE `t_admins` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_pseudo` varchar(100) DEFAULT NULL,
  `admin_mail` varchar(100) DEFAULT NULL,
  `admin_password` varchar(100) DEFAULT NULL,
  `admin_lvl` int(11) DEFAULT NULL,
  `admin_active` int(11) DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

/*Data for the table `t_admins` */

insert  into `t_admins`(`admin_id`,`admin_pseudo`,`admin_mail`,`admin_password`,`admin_lvl`,`admin_active`) values (25,'Demo User1','demo@demo.com','8gkHg+VRPaWJMzsFDH3RIYK6Ffg=',10,1),(26,'Demo User2','demo2@demo.com','8gkHg+VRPaWJMzsFDH3RIYK6Ffg=',10,1);

/*Table structure for table `t_clients` */

DROP TABLE IF EXISTS `t_clients`;

CREATE TABLE `t_clients` (
  `client_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_serial` varchar(20) DEFAULT NULL,
  `client_key` varchar(200) DEFAULT NULL,
  `client_hostname` varchar(200) DEFAULT NULL,
  `client_ip` varchar(200) DEFAULT NULL,
  `client_os` varchar(200) DEFAULT NULL,
  `client_os_version` varchar(200) DEFAULT NULL,
  `client_version` varchar(200) DEFAULT NULL,
  `client_privilege_access` int(11) DEFAULT NULL,
  `client_country` varchar(200) DEFAULT NULL,
  `client_socket` varchar(200) DEFAULT NULL,
  `client_active` int(11) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

/*Data for the table `t_clients` */

insert  into `t_clients`(`client_id`,`client_serial`,`client_key`,`client_hostname`,`client_ip`,`client_os`,`client_os_version`,`client_version`,`client_privilege_access`,`client_country`,`client_socket`,`client_active`) values (1,'000-000-000-001','03006512XcG3b8EMg9l1uU54ZUejzuog','DESKTOP-G9PJJ0E','::ffff:127.0.0.1','win32','x64','1.0.0 (beta)',0,'/','/nodeClient#9vv9GWlcV9bluyjzAAAB',1),(11,'000-000-000-011','03006512XcG3b8EMg9l1uU54ZUejzuog','DESKTOP-G9PJJ0E','::ffff:127.0.0.1','win32','x64','1.0.0 (beta)',0,'/','/nodeClient#l-owD1vF8v5lABvpAAAB',1),(10,'000-000-000-010','03006512XcG3b8EMg9l1uU54ZUejzuog','DESKTOP-G9PJJ0E','::ffff:127.0.0.1','win32','x64','1.0.0 (beta)',0,'/','/nodeClient#l-owD1vF8v5lABvpAAAB',1),(9,'000-000-000-009','03006512XcG3b8EMg9l1uU54ZUejzuog','DESKTOP-G9PJJ0E','::ffff:127.0.0.1','win32','x64','1.0.0 (beta)',0,'/','/nodeClient#l-owD1vF8v5lABvpAAAB',1),(8,'000-000-000-008','03006512XcG3b8EMg9l1uU54ZUejzuog','DESKTOP-G9PJJ0E','::ffff:127.0.0.1','win32','x64','1.0.0 (beta)',0,'/','/nodeClient#l-owD1vF8v5lABvpAAAB',1),(7,'000-000-000-007','03006512XcG3b8EMg9l1uU54ZUejzuog','DESKTOP-G9PJJ0E','::ffff:127.0.0.1','win32','x64','1.0.0 (beta)',0,'/','/nodeClient#l-owD1vF8v5lABvpAAAB',1),(6,'000-000-000-006','03006512XcG3b8EMg9l1uU54ZUejzuog','DESKTOP-G9PJJ0E','::ffff:127.0.0.1','win32','x64','1.0.0 (beta)',0,'/','/nodeClient#l-owD1vF8v5lABvpAAAB',1),(5,'000-000-000-005','03006512XcG3b8EMg9l1uU54ZUejzuog','DESKTOP-G9PJJ0E','::ffff:127.0.0.1','win32','x64','1.0.0 (beta)',0,'/','/nodeClient#l-owD1vF8v5lABvpAAAB',1),(4,'000-000-000-004','03006512XcG3b8EMg9l1uU54ZUejzuog','DESKTOP-G9PJJ0E','::ffff:127.0.0.1','win32','x64','1.0.0 (beta)',0,'/','/nodeClient#l-owD1vF8v5lABvpAAAB',1),(3,'000-000-000-003','03006512XcG3b8EMg9l1uU54ZUejzuog','DESKTOP-G9PJJ0E','::ffff:127.0.0.1','win32','x64','1.0.0 (beta)',0,'/','/nodeClient#l-owD1vF8v5lABvpAAAB',1),(2,'000-000-000-002','03006512XcG3b8EMg9l1uU54ZUejzuog','DESKTOP-G9PJJ0E','::ffff:127.0.0.1','win32','x64','1.0.0 (beta)',0,'/','/nodeClient#l-owD1vF8v5lABvpAAAB',1);

/*Table structure for table `t_configs` */

DROP TABLE IF EXISTS `t_configs`;

CREATE TABLE `t_configs` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_mode` varchar(10) DEFAULT NULL,
  `config_password` varchar(200) DEFAULT NULL,
  `config_active` int(11) DEFAULT NULL,
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `t_configs` */

insert  into `t_configs`(`config_id`,`config_mode`,`config_password`,`config_active`) values (1,'close','8gkHg+VRPaWJMzsFDH3RIYK6Ffg=',1);

/*Table structure for table `t_logs` */

DROP TABLE IF EXISTS `t_logs`;

CREATE TABLE `t_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_datetime` datetime DEFAULT NULL,
  `log_ip` varchar(100) DEFAULT NULL,
  `log_type` varchar(10) DEFAULT NULL,
  `log_origin` varchar(10) DEFAULT NULL,
  `log_text` text,
  `log_data` text,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_logs` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
