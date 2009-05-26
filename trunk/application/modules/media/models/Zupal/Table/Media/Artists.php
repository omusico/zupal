<?php

class Zupal_Table_Media_Artists extends Zupal_Table_Abstract
{

    protected $_id_field = 'artist_id';

    protected $_name = 'zupal_media_artists';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_media_artists` (
          `node_id` int(11) NOT NULL,
          `artist_id` int(10) unsigned NOT NULL auto_increment,
          `person_id` int(11) NOT NULL,
          `performs_as` varchar(150) NOT NULL,
          `media_id` int(11) NOT NULL,
          `bio` text NOT NULL,
          `mb_id` varchar(64) NOT NULL,
          `type` varchar(50) NOT NULL,
          PRIMARY KEY  (`artist_id`),
          KEY `node_id` (`node_id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=latin1");
    }

}

	