<?php

class Zupal_Table_People_Data
extends Zupal_Table_Abstract
{
	protected $_id_field = 'id';
	protected $_name = 'zupal_people_data';

	const INSTALL_TABLE = "CREATE TABLE IF NOT EXISTS  `zupal_people_data` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`people_id` INT NOT NULL ,
`type` VARCHAR( 20 ) NOT NULL ,
`info` VARCHAR( 255 ) NOT NULL ,
`weight` TINYINT NOT NULL
) ENGINE = MYISAM ;";

	public static function install()
	{
		Zupal_Database_Manager::get_adapter()->query(self::INSTALL_TABLE);
	}

}