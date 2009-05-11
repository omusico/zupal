<?php

class Zupal_Table_Employees
extends Zupal_Table_Abstract
{
	protected $_id_field = 'eid';
	protected $_name = 'zupal_employees';

	const INSTALL_TABLE = "CREATE TABLE `zupal_employees` (
  `eid` int(11) NOT NULL auto_increment,
  `node_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `manager` int(11) NOT NULL,
  `position` varchar(100) NOT NULL,
  `hire_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `fire_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `salary` float NOT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY  (`eid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

	public static function install()
	{
		if (!$this->table_exists()):
			Zupal_Database_Manager::get_adapter()->query(self::INSTALL_TABLE);
		endif;
	}

}