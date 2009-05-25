<?php

class Zupal_Table_Nodes extends Zupal_Table_Abstract
{

    protected $_id_field = 'node_id';

    protected $_name = 'zupal_nodes';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_nodes` (
          `node_id` int(11) unsigned NOT NULL auto_increment,
          `table` varchar(100) NOT NULL,
          `class` varchar(100) NOT NULL,
          `made` timestamp NOT NULL default CURRENT_TIMESTAMP,
          `version` int(11) NOT NULL,
          `status` int(11) NOT NULL,
          `sticky` tinyint(3) unsigned NOT NULL,
          PRIMARY KEY  (`node_id`),
          KEY `version` (`version`),
          KEY `sticky` (`sticky`),
          KEY `version_2` (`version`)
        ) ENGINE=MyISAM AUTO_INCREMENT=111 DEFAULT CHARSET=latin1");
    }

    protected function _init()
    {
        if(!$this->table_exists()) $this->create_table();
    }


}

	