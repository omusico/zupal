-- phpMyAdmin SQL Dump
-- version 2.11.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 16, 2009 at 06:13 PM
-- Server version: 5.0.41
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zupal2`
--

-- --------------------------------------------------------

--
-- Table structure for table `zupal_acl`
--

CREATE TABLE `zupal_acl` (
  `id` int(11) NOT NULL auto_increment,
  `resource` varchar(45) collate utf8_bin NOT NULL,
  `role` varchar(45) collate utf8_bin NOT NULL,
  `allow` char(4) collate utf8_bin NOT NULL default '-',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

--
-- Dumping data for table `zupal_acl`
--

INSERT INTO `zupal_acl` VALUES(1, 'site_admin', 'editor', 'N');
INSERT INTO `zupal_acl` VALUES(2, 'user_admin', 'editor', 'Y');
INSERT INTO `zupal_acl` VALUES(3, 'user_admin', 'validted', 'Y');
INSERT INTO `zupal_acl` VALUES(4, 'user_admin', 'admin', 'N');
INSERT INTO `zupal_acl` VALUES(5, 'site_admin', 'admin', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `zupal_atoms`
--

CREATE TABLE `zupal_atoms` (
  `id` int(11) NOT NULL auto_increment,
  `atomic_id` int(11) NOT NULL,
  `version` int(11) NOT NULL,
  `lead` text collate utf8_bin NOT NULL,
  `title` varchar(255) collate utf8_bin NOT NULL,
  `content` text collate utf8_bin NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `author` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=13 ;

--
-- Dumping data for table `zupal_atoms`
--

INSERT INTO `zupal_atoms` VALUES(1, 1, 1, 0x57656c636f6d6520546f204d792053697465, 'Home Page', 0x54686973206973206d7920686f6d652070616765, '2009-10-10 17:28:56', 0, 100);

-- --------------------------------------------------------

--
-- Table structure for table `zupal_link`
--

CREATE TABLE `zupal_link` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `reference_id` int(11) NOT NULL,
  `target_class` varchar(50) collate utf8_bin NOT NULL,
  `target_id` int(11) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `made_on` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL,
  `module` varchar(50) collate utf8_bin NOT NULL,
  `title` char(128) collate utf8_bin NOT NULL,
  `body` text collate utf8_bin NOT NULL,
  `teaser` text collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `zupal_link`
--


-- --------------------------------------------------------

--
-- Table structure for table `zupal_links`
--

CREATE TABLE `zupal_links` (
  `int` int(11) NOT NULL auto_increment,
  `from_atom` int(11) NOT NULL,
  `to_atom` int(11) NOT NULL,
  `link_type` varchar(45) collate utf8_bin NOT NULL,
  `by_user` int(11) NOT NULL,
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'note -- indicates when atoms  are linked to -- not the latest entry in either atoms',
  PRIMARY KEY  (`int`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `zupal_links`
--


-- --------------------------------------------------------

--
-- Table structure for table `zupal_linktypes`
--

CREATE TABLE `zupal_linktypes` (
  `linktype` varchar(45) collate utf8_bin NOT NULL,
  `title` varchar(100) collate utf8_bin NOT NULL,
  `notes` varchar(200) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`linktype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `zupal_linktypes`
--

INSERT INTO `zupal_linktypes` VALUES('parent', 'Parent', '');

-- --------------------------------------------------------

--
-- Table structure for table `zupal_menus`
--

CREATE TABLE `zupal_menus` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(45) collate utf8_bin NOT NULL,
  `path` varchar(255) collate utf8_bin NOT NULL,
  `panel` varchar(50) collate utf8_bin NOT NULL,
  `label` varchar(45) collate utf8_bin NOT NULL,
  `created_by_module` varchar(45) collate utf8_bin NOT NULL,
  `resource` varchar(45) collate utf8_bin NOT NULL,
  `parent` int(11) NOT NULL,
  `module` varchar(45) collate utf8_bin NOT NULL,
  `controller` varchar(45) collate utf8_bin NOT NULL,
  `action` varchar(45) collate utf8_bin NOT NULL,
  `href` varchar(255) collate utf8_bin NOT NULL,
  `callback_class` varchar(45) collate utf8_bin NOT NULL,
  `parameters` varchar(255) collate utf8_bin NOT NULL,
  `if_module` tinyint(4) NOT NULL,
  `if_controller` tinyint(3) unsigned NOT NULL,
  `sort_by` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `path` (`path`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=951 ;

--
-- Dumping data for table `zupal_menus`
--

INSERT INTO `zupal_menus` VALUES(933, 'layout', 'administer-main:home:layout', 'main', 'Layout', 'administer', '', 924, 'administer', 'layout', 'index', '', '', '', 1, 0, 4);
INSERT INTO `zupal_menus` VALUES(931, 'resources', 'administer-main:home:users:resources', 'main', 'Resources', 'administer', '', 929, 'administer', 'users', 'resources', '', '', '', 1, 0, 2);
INSERT INTO `zupal_menus` VALUES(932, 'acl', 'administer-main:home:users:acl', 'main', 'Permissions', 'administer', '', 929, 'administer', 'users', 'acl', '', '', '', 1, 0, 3);
INSERT INTO `zupal_menus` VALUES(930, 'roles', 'administer-main:home:users:roles', 'main', 'Roles', 'administer', '', 929, 'administer', 'users', 'roles', '', '', '', 1, 0, 1);
INSERT INTO `zupal_menus` VALUES(929, 'users', 'administer-main:home:users', 'main', 'Users', 'administer', '', 924, 'administer', 'users', 'index', '', '', '', 1, 0, 3);
INSERT INTO `zupal_menus` VALUES(928, 'editmenu', 'administer-main:home:editmenu', 'main', 'Edit Menu', 'administer', '', 924, 'administer', 'modules', 'menuedit', '', '', '', 1, 1, 2);
INSERT INTO `zupal_menus` VALUES(927, 'meta', 'administer-main:home:modules:meta', 'main', 'Edit Modules', 'administer', '', 925, 'administer', 'meta', 'index', '', '', '', 1, 1, 2);
INSERT INTO `zupal_menus` VALUES(934, 'home', 'default-main:home', 'main', 'Home', 'default', '', 0, 'default', 'index', 'index', '', '', '', 0, 0, 0);
INSERT INTO `zupal_menus` VALUES(925, 'modules', 'administer-main:home:modules', 'main', 'Modules', 'administer', '', 924, 'administer', 'modules', 'index', '', '', '', 1, 0, 1);
INSERT INTO `zupal_menus` VALUES(926, 'newmodule', 'administer-main:home:modules:newmodule', 'main', 'Add Module', 'administer', '', 925, 'administer', 'modules', 'new', '', '', '', 1, 1, 1);
INSERT INTO `zupal_menus` VALUES(950, 'admin', 'pages-main:home:admin', 'main', 'Administer Pages', 'pages', 'site_admin', 949, 'pages', 'admin', 'index', '', '', '', 1, 0, 1);
INSERT INTO `zupal_menus` VALUES(924, 'home', 'administer-main:home', 'main', 'Administer', 'administer', 'site_admin', 0, 'administer', 'index', 'index', '', '', '', 0, 0, 1);
INSERT INTO `zupal_menus` VALUES(938, 'find', '', 'main', 'Find User', '', '0', 924, 'administer', 'modules', 'menueditexecute', '', '', '', 1, 1, 0);
INSERT INTO `zupal_menus` VALUES(949, 'home', 'pages-main:home', 'main', 'Pages', 'pages', '', 0, 'pages', 'index', 'index', '', '', '', 0, 0, 10);

-- --------------------------------------------------------

--
-- Table structure for table `zupal_modules`
--

CREATE TABLE `zupal_modules` (
  `folder` varchar(45) collate utf8_bin NOT NULL,
  `sort_by` tinyint(3) unsigned NOT NULL,
  `title` varchar(100) collate utf8_bin NOT NULL,
  `notes` text collate utf8_bin NOT NULL,
  `version` varchar(45) collate utf8_bin NOT NULL,
  `required` tinyint(4) NOT NULL default '0',
  `package` varchar(50) collate utf8_bin NOT NULL,
  `active` tinyint(3) unsigned NOT NULL default '0',
  `resource_loaded` tinyint(4) NOT NULL default '0',
  `menu_loaded` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`folder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `zupal_modules`
--

INSERT INTO `zupal_modules` VALUES('administer', 0, 'Administer', 0x54686520636f7265206d6f64756c65206d616e61676572, '', 1, 'Zupal', 0, 0, 1);
INSERT INTO `zupal_modules` VALUES('default', 0, 'Home', 0x54686520486f6d652050616765, '', 1, 'Zupal', 0, 0, 1);
INSERT INTO `zupal_modules` VALUES('pages', 0, 'Pages', 0x47656e6572616c20436f6e74656e74204d616e61676572, '', 0, 'Pages', 1, 0, 1);
INSERT INTO `zupal_modules` VALUES('amia', 0, '', '', '', 0, '', 0, 0, 0);
INSERT INTO `zupal_modules` VALUES('mia', 0, '', '', '', 0, '', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `zupal_pages`
--

CREATE TABLE `zupal_pages` (
  `id` int(11) NOT NULL auto_increment,
  `atomic_id` int(11) NOT NULL,
  `resource` varchar(100) collate utf8_bin NOT NULL,
  `author` int(11) NOT NULL,
  `publish_status` varchar(45) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=13 ;

--
-- Dumping data for table `zupal_pages`
--

INSERT INTO `zupal_pages` VALUES(1, 1, '0', 0, 'published');

-- --------------------------------------------------------

--
-- Table structure for table `zupal_pagestatuses`
--

CREATE TABLE `zupal_pagestatuses` (
  `status` varchar(45) collate utf8_bin NOT NULL,
  `title` varchar(200) collate utf8_bin NOT NULL,
  `rank` tinyint(4) NOT NULL,
  PRIMARY KEY  (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `zupal_pagestatuses`
--

INSERT INTO `zupal_pagestatuses` VALUES('published', 'Published', 100);
INSERT INTO `zupal_pagestatuses` VALUES('approved', 'Approved', 50);
INSERT INTO `zupal_pagestatuses` VALUES('rejected', 'Rejected', -10);
INSERT INTO `zupal_pagestatuses` VALUES('flagged', 'Flagged', 0);
INSERT INTO `zupal_pagestatuses` VALUES('created', 'Created', 2);
INSERT INTO `zupal_pagestatuses` VALUES('updated', 'Updated', 3);
INSERT INTO `zupal_pagestatuses` VALUES('contributed', 'Contributed', 1);
INSERT INTO `zupal_pagestatuses` VALUES('archived', 'Archived', -5);

-- --------------------------------------------------------

--
-- Table structure for table `zupal_resources`
--

CREATE TABLE `zupal_resources` (
  `resource_id` varchar(45) collate utf8_bin NOT NULL,
  `title` varchar(100) collate utf8_bin NOT NULL,
  `content` text collate utf8_bin NOT NULL,
  `rank` tinyint(4) NOT NULL,
  `module` varchar(45) collate utf8_bin NOT NULL default 'zupal',
  PRIMARY KEY  (`resource_id`),
  FULLTEXT KEY `notes` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `zupal_resources`
--

INSERT INTO `zupal_resources` VALUES('site_admin', 'Administer Site', '', 10, 'administer');
INSERT INTO `zupal_resources` VALUES('user_admin', 'Administer Users', 0x546865206162696c69747920746f206164642c20656469742c2064656c65746520616e6420616c746572207065726d697373696f6e73206f6e2075736572732e20, 9, 'administer');

-- --------------------------------------------------------

--
-- Table structure for table `zupal_roles`
--

CREATE TABLE `zupal_roles` (
  `role_id` varchar(45) collate utf8_bin NOT NULL,
  `title` varchar(100) collate utf8_bin NOT NULL,
  `notes` text collate utf8_bin NOT NULL,
  `rank` tinyint(4) NOT NULL,
  `module` varchar(45) collate utf8_bin NOT NULL default 'zupal',
  PRIMARY KEY  (`role_id`),
  FULLTEXT KEY `notes` (`notes`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `zupal_roles`
--

INSERT INTO `zupal_roles` VALUES('anonymous', 'Anonymous User', 0x546865206d696e696d616c20726f6c6520666f722070656f706c652076696577696e6720746865207369746520776974686f7574206c6f6767696e6720696e2e2050726976696c6567657320666f72207468697320726f6c652077696c6c206170706c7920746f20616e796f6e652e, 0, 'zupal');
INSERT INTO `zupal_roles` VALUES('unvalidated', 'Unvalidated User', 0x4120757365722077686f736520656d61696c206164647265737320686173206e6f74206265656e20636f6f62657261746564, 1, 'zupal');
INSERT INTO `zupal_roles` VALUES('validted', 'Validated User', 0x4120757365722077686f736520656d61696c206164647265737320686173206265656e2076616c696461746564, 2, 'zupal');
INSERT INTO `zupal_roles` VALUES('admin', 'Administrator', 0x41207573657220776974682061646d696e6973747261746976652070726976696c65676573, 127, 'zupal');
INSERT INTO `zupal_roles` VALUES('editor', 'Editor', 0x412070726976696c656765642075736572207769746820746865206162696c69747920746f206d616e61676520636f6e74656e742c20627574206e6f742074686520656e7469726520736974652e20, 100, 'zupal');

-- --------------------------------------------------------

--
-- Table structure for table `zupal_users`
--

CREATE TABLE `zupal_users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(45) collate utf8_bin NOT NULL,
  `password` varchar(45) collate utf8_bin NOT NULL,
  `email` varchar(100) collate utf8_bin NOT NULL,
  `nid` int(10) unsigned NOT NULL,
  `vid` int(10) unsigned NOT NULL,
  `role` varchar(45) collate utf8_bin NOT NULL default 'anonymous',
  `status` set('active','validated','obsolete','deleted','banned','duplicate') collate utf8_bin NOT NULL default 'active',
  PRIMARY KEY  (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

--
-- Dumping data for table `zupal_users`
--

INSERT INTO `zupal_users` VALUES(2, 'test_user', 'c8EMdkVkPPH.M', 'test@pass.com', 0, 0, 'anonymous', 'active');
INSERT INTO `zupal_users` VALUES(3, 'admin', 'ecmR9gGXhGBFA', 'bingomantee@me.com', 0, 0, 'admin', 'active');
