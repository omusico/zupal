<?php

class Zupal_Table_Modules
extends Zupal_Table_Abstract
{
	protected $_id_field = 'name';
	protected $_name = 'zupal_modules';

	public static function install()
	{
		if ($this->table_exists()) return;
		$this->getAdapter()->query("CREATE TABLE `zupal_modules` (
  `name` varchar(100) NOT NULL,
  `enabled` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
	}
}