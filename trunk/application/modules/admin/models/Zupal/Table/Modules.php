<?php

class Zupal_Table_Modules extends Zupal_Table_Abstract
{

    protected $_id_field = 'name';

    protected $_name = 'zupal_modules';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_modules` (
          `name` varchar(100) NOT NULL,
          `description` text NOT NULL,
          `enabled` tinyint(4) NOT NULL default '0',
          `required` tinyint(4) NOT NULL,
          `version` varchar(50) NOT NULL,
          `menu` varchar(100) NOT NULL,
          `package` varchar(50) NOT NULL default '0',
          `made` timestamp NOT NULL default CURRENT_TIMESTAMP,
          PRIMARY KEY  (`name`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1");
    }


}

	