<?php

class Zupal_Table_Place_Countries extends Zupal_Table_Abstract
{

    protected $_id_field = null;

    protected $_name = 'zupal_place_countries';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_place_countries` (
          `name` varchar(255) NOT NULL,
          `code` varchar(3) NOT NULL,
          `has_states` tinyint(4) NOT NULL default '1',
          `lat` float NOT NULL,
          `long` float NOT NULL,
          PRIMARY KEY  (`code`)
        ) ENGINE=MyISAM AUTO_INCREMENT=247 DEFAULT CHARSET=latin1");
    }

    protected function _init()
    {
        if(!$this->table_exists()) $this->create_table();
    }


}

	