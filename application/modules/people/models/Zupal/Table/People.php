<?php

class Zupal_Table_People
extends Zupal_Table_Abstract
{
	protected $_id_field = 'person_id';
	protected $_name = 'zupal_people';

	const INSTALL_TABLE = "CREATE TABLE IF NOT EXISTS `zupal_people` (
  `person_id` int(10) unsigned NOT NULL auto_increment,
  `name_first` varchar(100) NOT NULL,
  `name_last` varchar(100) NOT NULL,
  `name_middle` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(64) NOT NULL,
  `username` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

	const INSTALL_PEOPLE_PLACE_TABLE = "CREATE TABLE IF NOT EXISTS  `zupal_people_places` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`person` INT NOT NULL ,
`place` INT NOT NULL ,
`type` VARCHAR( 50 ) NOT NULL ,
`weight` TINYINT NOT NULL ,
INDEX ( `person` , `place` )
) ENGINE = MYISAM ;";
	
	public static function install()
	{
		Zupal_Database_Manager::get_adapter()->query(self::INSTALL_TABLE);
		Zupal_Database_Manager::get_adapter()->query(self::INSTALL_PEOPLE_PLACE_TABLE);
	}

}