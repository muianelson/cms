-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 16, 2020 at 02:26 PM
-- Server version: 5.7.26-log
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `contracts`
--

-- --------------------------------------------------------

--
-- Table structure for table `addendums`
--

DROP TABLE IF EXISTS `addendums`;
CREATE TABLE IF NOT EXISTS `addendums` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `contractid` int(10) NOT NULL,
  `addendum` varchar(250) NOT NULL,
  `description` varchar(200) NOT NULL,
  `datetime` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

DROP TABLE IF EXISTS `contracts`;
CREATE TABLE IF NOT EXISTS `contracts` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `contractname` varchar(100) NOT NULL,
  `contractdetail` longtext NOT NULL,
  `executiondate` date DEFAULT NULL,
  `inputter` varchar(30) DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `comment` longtext,
  `departmentid` int(10) DEFAULT NULL,
  `expiry_date` date NOT NULL,
  `alert_duration` int(50) NOT NULL,
  `date_time` varchar(100) NOT NULL,
  `upload_file` varchar(500) NOT NULL,
  `value` varchar(20) NOT NULL,
  `renewal` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=529 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contractsaudit`
--

DROP TABLE IF EXISTS `contractsaudit`;
CREATE TABLE IF NOT EXISTS `contractsaudit` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `contractname` varchar(30) NOT NULL,
  `contractdetail` longtext NOT NULL,
  `executiondate` date DEFAULT NULL,
  `inputter` varchar(30) DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `comment` longtext,
  `departmentid` int(10) DEFAULT NULL,
  `expiry_date` date NOT NULL,
  `date_time` varchar(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  `alert_duration` int(10) NOT NULL,
  `renewal` varchar(10) DEFAULT NULL,
  `upload_file` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=398 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
CREATE TABLE IF NOT EXISTS `department` (
  `deptid` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `department` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  PRIMARY KEY (`deptid`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `emaillog`
--

DROP TABLE IF EXISTS `emaillog`;
CREATE TABLE IF NOT EXISTS `emaillog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sendto` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `datetime` varchar(250) NOT NULL,
  `fromadd` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `pageid` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `material_icon` varchar(30) NOT NULL,
  PRIMARY KEY (`pageid`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`pageid`, `page`, `description`, `material_icon`) VALUES
(1, 'dashboard.php', 'Dashboard', 'dashboard'),
(2, 'users.php', 'Users', 'person'),
(3, 'edit_user.php', 'Update user ', ''),
(4, 'contracts.php', 'Active Contracts', 'content_paste'),
(5, 'edit_contract.php', 'Update contract', ''),
(6, 'expired_contracts.php', 'Expired contracts', 'library_books'),
(7, 'addcontract.php', 'Add contract', 'bookmark'),
(8, 'departments.php', 'Departments', 'people_outline'),
(9, 'edit_department.php', 'Update department', ''),
(10, 'add_department.php', 'Add department', 'people'),
(11, 'user_roles.php', 'Roles', 'verified_user'),
(12, 'sysconfig.php', 'Sysconfig', 'settings'),
(13, 'reports.php', 'Reports', 'library_books'),
(14, 'advancedsearch.php', 'Advanced search', 'find_in_page'),
(15, 'audit.php', 'Audit', 'find_in_page'),
(17, 'archived_contracts.php', 'Archived contracts', 'archive'),
(20, 'contractswithoutvalues.php', 'Contracts without values', 'content_paste'),
(22, 'add_user.php', 'Add user', 'person'),
(25, 'expiring_month.php', 'Contracts expiring-30 days', 'content_paste'),
(24, 'incompletecontracts.php', 'Incomplete contracts', 'content_paste'),
(26, 'migrate_contracts.php', 'Migrate contracts', 'archive'),
(27, 'maintenance.php', 'Maintenance', 'settings');

-- --------------------------------------------------------

--
-- Table structure for table `passwordhistory`
--

DROP TABLE IF EXISTS `passwordhistory`;
CREATE TABLE IF NOT EXISTS `passwordhistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(150) NOT NULL,
  `password` varchar(200) NOT NULL,
  `PostingDate` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sysconfig`
--

DROP TABLE IF EXISTS `sysconfig`;
CREATE TABLE IF NOT EXISTS `sysconfig` (
  `confid` int(11) NOT NULL AUTO_INCREMENT,
  `companyname` varchar(200) NOT NULL,
  `fromemailaddress` varchar(60) NOT NULL,
  `companylogo` varchar(100) NOT NULL,
  `smtp_ip` varchar(15) NOT NULL,
  `smtp_port` varchar(15) NOT NULL,
  `passwordhistory` int(10) NOT NULL,
  `passwordexpiry` int(10) NOT NULL,
  `lockout` int(10) NOT NULL,
  `alertdays` int(11) NOT NULL,
  `session_timeout` varchar(20) NOT NULL,
  `passwordlength` int(11) NOT NULL,
  `maxfailedlogins` int(10) NOT NULL,
  `url` varchar(500) NOT NULL,
  PRIMARY KEY (`confid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sysconfig`
--

INSERT INTO `sysconfig` (`confid`, `companyname`, `fromemailaddress`, `companylogo`, `smtp_ip`, `smtp_port`, `passwordhistory`, `passwordexpiry`, `lockout`, `alertdays`, `session_timeout`, `passwordlength`, `maxfailedlogins`, `url`) VALUES
(1, 'ABC Company', 'nmuia@nse.co.ke', 'uploads/8413abc.png', '25', 'mail', 2, 5, 22, 10, '3600', 7, 3, 'http://localhost/contractmanager/sys/');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `enabled` varchar(100) NOT NULL,
  `admin` int(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nameofuser` varchar(100) DEFAULT NULL,
  `lastlogindate` varchar(12) DEFAULT NULL,
  `logincount` int(11) DEFAULT NULL,
  `lastpasswordchangedate` varchar(12) DEFAULT NULL,
  `createdate` varchar(100) NOT NULL,
  `resetflag` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `enabled`, `admin`, `email`, `nameofuser`, `lastlogindate`, `logincount`, `lastpasswordchangedate`, `createdate`, `resetflag`) VALUES
(7, 'nmuia', 'cbe10224496fb0be0b7ca5178c932822', '1', 1, 'nmuiax@nse.co.ke', 'Nelson Muia ', '2020/07/16', 0, '2020/07/16', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_report_mapping`
--

DROP TABLE IF EXISTS `user_report_mapping`;
CREATE TABLE IF NOT EXISTS `user_report_mapping` (
  `userid` int(11) NOT NULL,
  `deptid` int(11) NOT NULL,
  UNIQUE KEY `userdept` (`userid`,`deptid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_report_mapping`
--

INSERT INTO `user_report_mapping` (`userid`, `deptid`) VALUES
(7, 1),
(7, 2),
(7, 3),
(7, 4),
(7, 5),
(7, 6),
(7, 10),
(7, 13),
(7, 14),
(7, 15);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE IF NOT EXISTS `user_roles` (
  `userid` int(10) NOT NULL,
  `pageid` int(10) NOT NULL,
  UNIQUE KEY `userpage` (`userid`,`pageid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`userid`, `pageid`) VALUES
(7, 2),
(7, 3),
(7, 4),
(7, 5),
(7, 6),
(7, 7),
(7, 8),
(7, 10),
(7, 11),
(7, 12),
(7, 13),
(7, 14),
(7, 15),
(7, 17),
(7, 20),
(7, 22),
(7, 24),
(7, 25),
(7, 26),
(7, 27);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
