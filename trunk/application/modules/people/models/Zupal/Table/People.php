<?php

class Zupal_Table_People extends Zupal_Table_Abstract
{

    protected $_id_field = 'person_id';

    protected $_name = 'zupal_people';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_people` (
          `person_id` int(10) unsigned NOT NULL auto_increment,
          `gender` varchar(1) default NULL,
          `title` varchar(20) NOT NULL,
          `name_first` varchar(100) default NULL,
          `name_last` varchar(100) default NULL,
          `name_middle` varchar(100) default NULL,
          `email` varchar(200) default NULL,
          `password` varchar(64) NOT NULL,
          `username` varchar(50) NOT NULL,
          `born` date default NULL,
          `died` date default NULL,
          `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
          PRIMARY KEY  (`person_id`),
          KEY `username` (`username`)
        ) ENGINE=MyISAM AUTO_INCREMENT=131 DEFAULT CHARSET=latin1");
    }

}

	