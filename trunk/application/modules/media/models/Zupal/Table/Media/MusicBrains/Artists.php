<?php

class Zupal_Table_Media_Musicbrains_Artists extends Zupal_Table_Abstract
{

    protected $_id_field = null;

    protected $_name = 'zupal_media_musicbrains_artists';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_media_musicbrains_artists` (
          `mb_id` varchar(64) NOT NULL,
          `name` varchar(255) NOT NULL,
          `type` varchar(20) NOT NULL,
          `begin` date NOT NULL,
          `end` date NOT NULL,
          `at` timestamp NOT NULL default CURRENT_TIMESTAMP,
          PRIMARY KEY  (`mb_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1");
    }

    protected function _init()
    {
        if(!$this->table_exists()) $this->create_table();
    }


}

	