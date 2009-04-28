<?php

class Zupal_Table_Content
extends Zupal_Table_Abstract
{
	protected $_id_field = 'id';
	protected $_name = 'zupal_content';

	const INSTALL_TABLE = "CREATE TABLE IF NOT EXISTS `zupal_content` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `node_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `short_title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `raw_text` text NOT NULL,
  `author_id` int(11) NOT NULL,
  `publish_date` timestamp NULL default NULL,
  `unpublish_date` timestamp NULL default NULL,
  `is_public` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;";

	public static function install()
	{
		Zupal_Database_Manager::get_adapter()->query(self::INSTALL_TABLE);
	}

}