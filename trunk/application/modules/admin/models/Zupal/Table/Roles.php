<?php

class Zupal_Table_Roles extends Zupal_Table_Abstract
{

    protected $_id_field = 'id';

    protected $_name = 'zupal_roles';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_roles` (
          `id` varchar(255) NOT NULL,
          `label` varchar(255) NOT NULL,
          `parent` varchar(255) NOT NULL,
          PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1");
    }


}

	