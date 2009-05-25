<?php

class Zupal_Table_People_Places extends Zupal_Table_Abstract
{

    protected $_id_field = 'id';

    protected $_name = 'zupal_people_places';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_people_places` (
          `id` int(10) unsigned NOT NULL auto_increment,
          `person` int(11) NOT NULL,
          `place` int(11) NOT NULL,
          `type` varchar(50) NOT NULL,
          `weight` tinyint(4) NOT NULL,
          PRIMARY KEY  (`id`),
          KEY `person` (`person`,`place`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1");
    }

    protected function _init()
    {
        if(!$this->table_exists()) $this->create_table();
    }


}

	