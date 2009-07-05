<?php

class Zupal_Table_Grants extends Zupal_Table_Abstract
{

    protected $_id_field = 'id';

    protected $_name = 'zupal_grants';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_grants` (
          `id` int(10) unsigned NOT NULL auto_increment,
          `resource` varchar(255) NOT NULL,
          `role` varchar(255) NOT NULL,
          `allow` tinyint(4) NOT NULL default '0',
          PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1");
    }


}

	