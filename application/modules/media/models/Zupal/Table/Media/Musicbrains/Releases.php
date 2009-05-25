<?php

class Zupal_Table_Media_Musicbrains_Releases extends Zupal_Table_Abstract
{

    protected $_id_field = null;

    protected $_name = 'zupal_media_musicbrains_releases';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_media_musicbrains_releases` (
          `mbid` varchar(64) NOT NULL,
          `title` varchar(200) NOT NULL,
          `artist` varchar(64) NOT NULL,
          `begin` date NOT NULL,
          `end` date NOT NULL,
          `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
          PRIMARY KEY  (`mbid`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1");
    }

    protected function _init()
    {
        if(!$this->table_exists()) $this->create_table();
    }


}

	