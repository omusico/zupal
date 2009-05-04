-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: May 03, 2009 at 08:05 AM
-- Server version: 5.0.41
-- PHP Version: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `zupal`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `zupal_content`
-- 

CREATE TABLE `zupal_content` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `node_id` int(11) default NULL,
  `title` varchar(255) NOT NULL,
  `short_title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `raw_text` text NOT NULL,
  `author_id` int(11) NOT NULL,
  `publish_date` timestamp NULL default NULL,
  `unpublish_date` timestamp NULL default NULL,
  `is_public` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

-- 
-- Dumping data for table `zupal_content`
-- 

INSERT INTO `zupal_content` VALUES (1, 2, 'This is gone', '', 'My Content', '', 0, '2009-04-27 08:57:27', NULL, 1);
INSERT INTO `zupal_content` VALUES (2, 3, 'First Try', '', 'Is the charm', '', 0, '2009-04-27 08:56:27', NULL, 1);
INSERT INTO `zupal_content` VALUES (3, 4, 'Make Me', '', 'Beuatiful', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (4, 5, 'Make Me', '', 'Beuatiful', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (5, 4, 'Make Me More', '', 'Even More Beautiful', '', 0, '2009-04-26 12:34:15', NULL, 1);
INSERT INTO `zupal_content` VALUES (6, NULL, 'Another Subject', '', 'My Subject', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (7, NULL, 'Another Subject', '', 'My Subject', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (8, NULL, 'Another Subject', '', 'My Subject', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (9, NULL, 'Another Subject', '', 'My Subject', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (10, NULL, 'Another Subject', '', 'My Subject', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (11, NULL, 'Another Subject', '', 'My Subject', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (12, NULL, 'Another Subject', '', 'My Subject', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (13, NULL, 'Another Subject', '', 'My Subject', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (14, NULL, 'Make Another Content', '', 'Content text', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (15, NULL, 'Make Another Content', '', 'Content text', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (16, 6, 'Make Another Content', '', 'Content text', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (17, 7, 'Here is one', '', 'A Content Block', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (18, 8, 'Here is one', '', 'A Content Block', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (19, 9, 'A Sticky One', '', 'This is a sticky wicket!', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (20, 10, 'A Sticky One', '', 'This is a sticky wicket!', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (21, 11, 'A Sticky One', '', 'This is a sticky wicket!', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (22, 12, 'Here is some more stickies', '', 'a content ', '', 0, NULL, NULL, 1);
INSERT INTO `zupal_content` VALUES (23, 13, 'Flag the Rabbit', '', 'The Rabbit', '', 0, NULL, NULL, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `zupal_nodes`
-- 

CREATE TABLE `zupal_nodes` (
  `node_id` int(11) unsigned NOT NULL auto_increment,
  `table` varchar(100) NOT NULL,
  `class` varchar(100) NOT NULL,
  `made` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `version` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `sticky` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`node_id`),
  KEY `version` (`version`),
  KEY `sticky` (`sticky`),
  KEY `version_2` (`version`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- 
-- Dumping data for table `zupal_nodes`
-- 

INSERT INTO `zupal_nodes` VALUES (1, '', '', '2009-04-25 19:15:41', 0, 0, 0);
INSERT INTO `zupal_nodes` VALUES (2, 'content', 'Zupal_Content', '2009-04-25 19:18:05', 1, 33, 0);
INSERT INTO `zupal_nodes` VALUES (3, 'content', 'Zupal_Content', '2009-04-25 19:18:46', 2, 0, 0);
INSERT INTO `zupal_nodes` VALUES (4, 'content', 'Zupal_Content', '2009-04-25 19:19:01', 5, 0, 0);
INSERT INTO `zupal_nodes` VALUES (5, 'content', 'Zupal_Content', '2009-04-25 19:19:26', 4, 3, 0);
INSERT INTO `zupal_nodes` VALUES (6, '', '', '2009-04-26 14:08:30', 0, 0, 0);
INSERT INTO `zupal_nodes` VALUES (7, 'content', 'Zupal_Content', '2009-04-26 14:11:18', 17, 0, 0);
INSERT INTO `zupal_nodes` VALUES (8, 'content', 'Zupal_Content', '2009-04-26 14:13:14', 18, 1, 0);
INSERT INTO `zupal_nodes` VALUES (9, '', '', '2009-04-27 11:36:23', 19, 0, 0);
INSERT INTO `zupal_nodes` VALUES (10, '', '', '2009-04-27 12:01:42', 20, 5, 0);
INSERT INTO `zupal_nodes` VALUES (11, '', '', '2009-04-27 12:05:30', 21, 5, 0);
INSERT INTO `zupal_nodes` VALUES (12, '', '', '2009-04-27 12:13:12', 22, 19, 0);
INSERT INTO `zupal_nodes` VALUES (13, 'content', 'Zupal_Content', '2009-04-27 12:22:55', 23, 9, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `zupal_people`
-- 

CREATE TABLE `zupal_people` (
  `person_id` int(10) unsigned NOT NULL auto_increment,
  `name_first` varchar(100) NOT NULL,
  `name_last` varchar(100) NOT NULL,
  `name_middle` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(64) NOT NULL,
  `username` varchar(50) NOT NULL,
  PRIMARY KEY  (`person_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `zupal_people`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `zupal_people_data`
-- 

CREATE TABLE `zupal_people_data` (
  `id` int(11) NOT NULL auto_increment,
  `people_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `info` varchar(255) NOT NULL,
  `weight` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `zupal_people_data`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `zupal_people_places`
-- 

CREATE TABLE `zupal_people_places` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `person` int(11) NOT NULL,
  `place` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `weight` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `person` (`person`,`place`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `zupal_people_places`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `zupal_places`
-- 

CREATE TABLE `zupal_places` (
  `place_id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `lat` float NOT NULL,
  `long` float NOT NULL,
  `addr` varchar(255) NOT NULL,
  `addr2` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `city_id` int(11) NOT NULL,
  `state` varchar(20) NOT NULL,
  `state_id` int(11) NOT NULL,
  `country` varchar(50) NOT NULL,
  `country_id` varchar(6) NOT NULL,
  `postalcode` varchar(20) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY  (`place_id`),
  KEY `state` (`state_id`),
  KEY `city` (`city`),
  KEY `country` (`country_id`),
  KEY `postalcode` (`postalcode`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- 
-- Dumping data for table `zupal_places`
-- 

INSERT INTO `zupal_places` VALUES (1, 'My House', 0, 0, '', '', 'San Francisco', 0, 'CA', 0, 'United States', 'US', '', '');
INSERT INTO `zupal_places` VALUES (2, 'My House', 0, 0, '', '', 'San Francisco', 0, 'CA', 0, 'United States', 'US', '', '');
INSERT INTO `zupal_places` VALUES (3, 'My House', 0, 0, '', '', 'San Francisco', 0, 'CA', 0, 'United States', 'US', '', '');
INSERT INTO `zupal_places` VALUES (4, 'My House', 0, 0, '', '', 'San Francisco', 0, 'CA', 0, 'United States', 'US', '', '');
INSERT INTO `zupal_places` VALUES (5, 'My House', 0, 0, '', '', 'San Francisco', 0, 'CA', 0, 'United States', 'US', '', '');
INSERT INTO `zupal_places` VALUES (6, 'My House', 0, 0, '', '', 'San Francisco', 0, 'CA', 0, 'United States', 'US', '', '');
INSERT INTO `zupal_places` VALUES (7, 'My House', 0, 0, '', '', 'San Francisco', 0, 'CA', 0, 'United States', 'US', '', '');
INSERT INTO `zupal_places` VALUES (8, 'My House', 0, 0, '1045 Mission', '#414', 'San Francisco', 0, 'CA', 0, 'United States', 'US', '', '');
INSERT INTO `zupal_places` VALUES (9, 'My House', 0, 0, '1045 Mission', '#414', 'San Francisco', 0, 'CA', 0, 'United States', 'US', '94103', '');
INSERT INTO `zupal_places` VALUES (10, 'My Made Up Place', 0, 0, 'an place', '55', 'madeupville', 0, 'madeupstate', 68, 'Austria', 'AT', '26246743', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `zupal_place_cities`
-- 

CREATE TABLE `zupal_place_cities` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `lat` float NOT NULL,
  `long` float NOT NULL,
  `state` int(11) NOT NULL,
  `country` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `state` (`state`),
  KEY `country` (`country`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `zupal_place_cities`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `zupal_place_countries`
-- 

CREATE TABLE `zupal_place_countries` (
  `name` varchar(255) NOT NULL,
  `code` varchar(3) NOT NULL,
  `has_states` tinyint(4) NOT NULL default '1',
  `lat` float NOT NULL,
  `long` float NOT NULL,
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `zupal_place_countries`
-- 

INSERT INTO `zupal_place_countries` VALUES ('Andorra', 'AD', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('United Arab Emirates', 'AE', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Afghanistan', 'AF', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Antigua and Barbuda', 'AG', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Anguilla', 'AI', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Albania', 'AL', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Armenia', 'AM', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Netherlands Antilles', 'AN', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Angola', 'AO', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Antarctica', 'AQ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Argentina', 'AR', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('American Samoa', 'AS', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Austria', 'AT', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Australia', 'AU', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Aruba', 'AW', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Aland Islands', 'AX', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Azerbaijan', 'AZ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Bosnia and Herzegovina', 'BA', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Barbados', 'BB', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Bangladesh', 'BD', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Belgium', 'BE', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Burkina Faso', 'BF', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Bulgaria', 'BG', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Bahrain', 'BH', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Burundi', 'BI', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Benin', 'BJ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Saint Barthélemy', 'BL', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Bermuda', 'BM', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Brunei Darussalam', 'BN', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Bolivia', 'BO', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Brazil', 'BR', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Bahamas', 'BS', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Bhutan', 'BT', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Bouvet Island', 'BV', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Botswana', 'BW', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Belarus', 'BY', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Belize', 'BZ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Canada', 'CA', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Cocos (Keeling) Islands', 'CC', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Congo, Dem. Republic of', 'CD', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Central African Republic', 'CF', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Congo', 'CG', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Switzerland', 'CH', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Cote d''Ivoire', 'CI', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Cook Islands', 'CK', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Chile', 'CL', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Cameroon', 'CM', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('China', 'CN', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Colombia', 'CO', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Costa Rica', 'CR', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Cuba', 'CU', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Cape Verde', 'CV', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Christmas Island', 'CX', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Cyprus', 'CY', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Czech Republic', 'CZ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Germany', 'DE', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Djibouti', 'DJ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Denmark', 'DK', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Dominica', 'DM', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Dominican Republic', 'DO', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Algeria', 'DZ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Ecuador', 'EC', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Estonia', 'EE', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Egypt', 'EG', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Western Sahara', 'EH', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Eritrea', 'ER', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Spain', 'ES', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Ethiopia', 'ET', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Finland', 'FI', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Fiji', 'FJ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Falkland Islands (Malvinas)', 'FK', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Micronesia, Fed. States of', 'FM', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Faroe Islands', 'FO', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('France', 'FR', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Gabon', 'GA', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('United Kingdom', 'GB', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Grenada', 'GD', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Georgia', 'GE', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('French Guiana', 'GF', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Guernsey', 'GG', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Ghana', 'GH', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Gibraltar', 'GI', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Greenland', 'GL', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Gambia', 'GM', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Guinea', 'GN', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Guadeloupe', 'GP', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Equatorial Guinea', 'GQ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Greece', 'GR', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('S. Georgia & S. Sandwich Is.', 'GS', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Guatemala', 'GT', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Guam', 'GU', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Guinea-Bissau', 'GW', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Guyana', 'GY', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Hong Kong', 'HK', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Heard Island & McDonald Is.', 'HM', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Honduras', 'HN', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Croatia', 'HR', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Haiti', 'HT', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Hungary', 'HU', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Indonesia', 'ID', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Ireland', 'IE', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Israel', 'IL', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Isle of Man', 'IM', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('India', 'IN', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('British Indian Ocean Territory', 'IO', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Iraq', 'IQ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Iran, Islamic Republic of', 'IR', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Iceland', 'IS', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Italy', 'IT', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Jersey', 'JE', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Jamaica', 'JM', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Jordan', 'JO', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Japan', 'JP', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Kenya', 'KE', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Kyrgyzstan', 'KG', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Cambodia', 'KH', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Kiribati', 'KI', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Comoros', 'KM', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Saint Kitts and Nevis', 'KN', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Korea, DPR', 'KP', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Korea, Republic of', 'KR', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Kuwait', 'KW', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Cayman Islands', 'KY', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Kazakhstan', 'KZ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Lao PDR', 'LA', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Lebanon', 'LB', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Saint Lucia', 'LC', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Liechtenstein', 'LI', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Sri Lanka', 'LK', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Liberia', 'LR', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Lesotho', 'LS', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Lithuania', 'LT', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Luxembourg', 'LU', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Latvia', 'LV', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Libyan Arab Jamahiriya', 'LY', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Morocco', 'MA', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Monaco', 'MC', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Moldova, Republic of', 'MD', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Montenegro', 'ME', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Saint Martin (French part)', 'MF', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Madagascar', 'MG', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Marshall Islands', 'MH', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Macedonia', 'MK', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Mali', 'ML', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Myanmar', 'MM', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Mongolia', 'MN', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Macao', 'MO', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Northern Mariana Islands', 'MP', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Martinique', 'MQ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Mauritania', 'MR', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Montserrat', 'MS', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Malta', 'MT', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Mauritius', 'MU', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Maldives', 'MV', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Malawi', 'MW', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Mexico', 'MX', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Malaysia', 'MY', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Mozambique', 'MZ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Namibia', 'NA', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('New Caledonia', 'NC', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Niger', 'NE', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Norfolk Island', 'NF', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Nigeria', 'NG', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Nicaragua', 'NI', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Netherlands', 'NL', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Norway', 'NO', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Nepal', 'NP', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Nauru', 'NR', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Niue', 'NU', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('New Zealand', 'NZ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Oman', 'OM', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Panama', 'PA', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Peru', 'PE', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('French Polynesia', 'PF', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Papua New Guinea', 'PG', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Philippines', 'PH', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Pakistan', 'PK', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Poland', 'PL', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Saint Pierre and Miquelon', 'PM', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Pitcairn', 'PN', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Puerto Rico', 'PR', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Palestinian Territory', 'PS', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Portugal', 'PT', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Palau', 'PW', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Paraguay', 'PY', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Qatar', 'QA', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Reunion Réunion', 'RE', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Romania', 'RO', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Serbia', 'RS', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Russian Federation', 'RU', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Rwanda', 'RW', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Saudi Arabia', 'SA', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Solomon Islands', 'SB', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Seychelles', 'SC', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Sudan', 'SD', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Sweden', 'SE', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Singapore', 'SG', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Saint Helena', 'SH', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Slovenia', 'SI', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Svalbard and Jan Mayen', 'SJ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Slovakia', 'SK', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Sierra Leone', 'SL', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('San Marino', 'SM', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Senegal', 'SN', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Somalia', 'SO', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Suriname', 'SR', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('São Tomé and Príncipe', 'ST', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('El Salvador', 'SV', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Syrian Arab Republic', 'SY', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Swaziland', 'SZ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Turks and Caicos Islands', 'TC', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Chad', 'TD', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('French Southern Territories', 'TF', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Togo', 'TG', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Thailand', 'TH', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Tajikistan', 'TJ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Tokelau', 'TK', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Timor-Leste', 'TL', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Turkmenistan', 'TM', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Tunisia', 'TN', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Tonga', 'TO', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Turkey', 'TR', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Trinidad and Tobago', 'TT', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Tuvalu', 'TV', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Taiwan, Province of China', 'TW', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Tanzania, United Republic of', 'TZ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Ukraine', 'UA', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Uganda', 'UG', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('US Minor Outlying Islands', 'UM', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('United States', 'US', 1, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Uruguay', 'UY', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Uzbekistan', 'UZ', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Holy See (Vatican City State)', 'VA', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Saint Vincent & Grenadines', 'VC', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Venezuela', 'VE', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Virgin Islands, British', 'VG', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Virgin Islands, U.S.', 'VI', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Viet Nam', 'VN', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Vanuatu', 'VU', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Wallis and Futuna', 'WF', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Samoa', 'WS', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Yemen', 'YE', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Mayotte', 'YT', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('South Africa', 'ZA', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Zambia', 'ZM', 0, 0, 0);
INSERT INTO `zupal_place_countries` VALUES ('Zimbabwe', 'ZW', 0, 0, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `zupal_place_states`
-- 

CREATE TABLE `zupal_place_states` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `code` varchar(5) NOT NULL,
  `lat` float NOT NULL,
  `long` float NOT NULL,
  `country` varchar(5) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `country` (`country`),
  KEY `name` (`name`),
  KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=69 ;

-- 
-- Dumping data for table `zupal_place_states`
-- 

INSERT INTO `zupal_place_states` VALUES (2, 'Armed Forces Europe', 'AE', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (3, 'Alaska', 'AK', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (4, 'Alabama', 'AL', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (5, 'Pacific', 'AP', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (6, 'Arkansas', 'AR', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (7, 'American Samoa', 'AS', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (8, 'Arizona', 'AZ', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (9, 'California', 'CA', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (10, 'Commonwealth of the Northern Mariana Islands', 'CM', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (11, 'Colorado', 'CO', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (12, 'Connecticut', 'CT', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (13, 'Canal Zone', 'CZ', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (14, 'District of Columbia', 'DC', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (15, 'Delaware', 'DE', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (16, 'Florida', 'FL', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (17, 'Federated States of Micronesia', 'FM', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (18, 'Georgia', 'GA', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (19, 'Guam', 'GU', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (20, 'Hawaii', 'HI', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (21, 'Iowa', 'IA', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (22, 'Idaho', 'ID', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (23, 'Illinois', 'IL', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (24, 'Indiana', 'IN', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (25, 'Kansas', 'KS', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (26, 'Kentucky', 'KY', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (27, 'Louisiana', 'LA', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (28, 'Massachusetts', 'MA', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (29, 'Maryland', 'MD', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (30, 'Maine', 'ME', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (31, 'Marshall Islands', 'MH', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (32, 'Michigan', 'MI', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (33, 'Minnesota', 'MN', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (34, 'Missouri', 'MO', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (35, 'Northern Mariana Islands', 'MP', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (36, 'Mississippi', 'MS', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (37, 'Montana', 'MT', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (38, 'North Carolina', 'NC', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (39, 'North Dakota', 'ND', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (40, 'Nebraska', 'NE', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (41, 'New Hampshire', 'NH', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (42, 'New Jersey', 'NJ', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (43, 'New Mexico', 'NM', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (44, 'Nevada', 'NV', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (45, 'New York', 'NY', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (46, 'Ohio', 'OH', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (47, 'Oklahoma', 'OK', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (48, 'Oregon', 'OR', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (49, 'Pennsylvania', 'PA', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (50, 'Philippine Islands', 'PI', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (51, 'Puerto Rico', 'PR', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (52, 'Palau', 'PW', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (53, 'Rhode Island and Providence Plantations', 'RI', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (54, 'South Carolina', 'SC', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (55, 'South Dakota', 'SD', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (56, 'Tennessee', 'TN', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (57, 'Trust Territory of the Pacific Islands', 'TT', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (58, 'Texas', 'TX', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (59, 'Utah', 'UT', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (60, 'Virginia', 'VA', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (61, 'Virgin Islands', 'VI', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (62, 'Vermont', 'VT', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (63, 'Washington', 'WA', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (64, 'Wisconsin', 'WI', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (65, 'West Virginia', 'WV', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (66, 'Wyoming', 'WY', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (67, 'Armed Forces America', 'AA', 0, 0, 'US');
INSERT INTO `zupal_place_states` VALUES (68, 'madeupstate', '', 0, 0, '0');

-- --------------------------------------------------------

-- 
-- Table structure for table `zupal_terms`
-- 

CREATE TABLE `zupal_terms` (
  `term_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `weight` tinyint(4) NOT NULL,
  PRIMARY KEY  (`term_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `zupal_terms`
-- 

