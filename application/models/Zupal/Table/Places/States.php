<?php

class Zupal_Table_Place_States extends Zupal_Table_Abstract
{

    protected $_id_field = 'id';

    protected $_name = 'zupal_place_states';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_place_states` (
          `id` int(10) unsigned NOT NULL auto_increment,
          `name` varchar(255) NOT NULL,
          `code` varchar(5) NOT NULL,
          `lat` float NOT NULL,
          `long` float NOT NULL,
          `country` varchar(5) NOT NULL,
          PRIMARY KEY  (`id`),
          KEY `country` (`country`),
          KEY `name` (`name`),
          KEY `code` (`code`)
        ) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=latin1");
    }

    protected function _init()
    {
        if(!$this->table_exists()) $this->create_table();
    }


}

	