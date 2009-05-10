<?php

class Zupal_Table_Eventlogs
extends Zupal_Table_Abstract
{
	protected $_id_field = 'place_id';
	protected $_name = 'zupal_eventlogs';

	public static function install()
	{
		if ($this->table_exists()) return;
		$this->getAdapter()->query('CREATE TABLE `zupal_eventlogs` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`message` TEXT NOT NULL ,
`priority` TINYINT NOT NULL ,
`priorityName` VARCHAR( 20 ) NOT NULL ,
`module` VARCHAR( 40 ) NOT NULL ,
`action` VARCHAR( 40 ) NOT NULL ,
`params` TEXT NOT NULL
) ENGINE = MYISAM ;');
	}
}