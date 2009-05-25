<?php

class Zupal_Table_Place_Cities extends Zupal_Table_Abstract
{

    protected $_id_field = 'id';

    protected $_name = 'zupal_place_cities';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_place_cities` (
          `id` int(10) unsigned NOT NULL auto_increment,
          `name` varchar(255) NOT NULL,
          `lat` float NOT NULL,
          `long` float NOT NULL,
          `state` int(11) default NULL,
          `country` int(11) NOT NULL,
          PRIMARY KEY  (`id`),
          KEY `state` (`state`),
          KEY `country` (`country`)
        ) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1");
    }

    protected function _init()
    {
        if(!$this->table_exists()) $this->create_table();
    }


}

	