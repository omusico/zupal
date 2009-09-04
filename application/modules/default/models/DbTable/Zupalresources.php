<?php

class Model_DbTable_Zupalresources extends Zupal_Table_Abstract
{

    protected $_name = 'zupal_resources';
    protected $_id_field = 'resource_id';

    const CREATE_SQL = "CREATE TABLE `zupal_resources` (
  `resource_id` varchar(45) collate utf8_bin NOT NULL,
  `title` varchar(100) collate utf8_bin NOT NULL,
  `notes` text collate utf8_bin NOT NULL,
  `rank` tinyint(4) NOT NULL,
  `module` varchar(45) collate utf8_bin NOT NULL default 'zupal',
  PRIMARY KEY  (`resource_id`),
  FULLTEXT KEY `notes` (`notes`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ create_table @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function create_table () {
        $adapter = Zupal_Database_Manager::get_adapter();
        $adapter->query(self::CREATE_SQL);
    }
}

