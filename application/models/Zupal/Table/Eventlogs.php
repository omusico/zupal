<?php

class Zupal_Table_Eventlogs extends Zupal_Table_Abstract
{

    protected $_id_field = 'id';

    protected $_name = 'zupal_eventlogs';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_eventlogs` (
          `id` int(10) unsigned NOT NULL auto_increment,
          `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
          `message` text NOT NULL,
          `priority` tinyint(4) NOT NULL,
          `priorityName` varchar(20) NOT NULL,
          `module` varchar(40) NOT NULL,
          `action` varchar(40) NOT NULL,
          `params` text NOT NULL,
          PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=231 DEFAULT CHARSET=latin1");
    }

    protected function _init()
    {
        if(!$this->table_exists()) $this->create_table();
    }


}

	