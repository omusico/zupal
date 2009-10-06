
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `urls`;
CREATE TABLE `urls` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `url` varchar(255) NOT NULL,
  `title` varchar(50) NOT NULL,
  `domain` int(100) NOT NULL,
  `path` varchar(255) NOT NULL,
  `query` varchar(255) NOT NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;



DROP TABLE IF EXISTS `url_domains`;
CREATE TABLE `url_domains` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `host` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;



DROP TABLE IF EXISTS `url_htmls`;
CREATE TABLE `url_htmls` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `in_url` int(11) NOT NULL,
  `html` text NOT NULL,
  `scanned_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;



DROP TABLE IF EXISTS `url_images`;
CREATE TABLE `url_images` (
  `id` int(11) NOT NULL auto_increment,
  `href` varchar(255) NOT NULL,
  `in_url` int(11) NOT NULL,
  `href_url` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;



DROP TABLE IF EXISTS `url_links`;
CREATE TABLE `url_links` (
  `id` int(11) NOT NULL auto_increment,
  `from_url` int(11) NOT NULL,
  `to_url` int(11) NOT NULL,
  `found_in_html` int(11) NOT NULL,
  `linked` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;
