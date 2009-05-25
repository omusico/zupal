<?php

class Zupal_Table_People_Contact extends Zupal_Table_Abstract
{

    protected $_id_field = 'id';

    protected $_name = 'zupal_people_contact';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_people_contact` (
          `id` int(11) NOT NULL auto_increment,
          `person` int(11) NOT NULL,
          `type` varchar(20) NOT NULL,
          `value` varchar(100) NOT NULL,
          `notes` varchar(200) NOT NULL,
          `status` tinyint(3) unsigned NOT NULL,
          PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1");
    }

    protected function _init()
    {
        if(!$this->table_exists()) $this->create_table();
    }


}

	