<?php

class Zupal_Table_Media_Media extends Zupal_Table_Abstract
{

    protected $_id_field = 'media_id';

    protected $_name = 'zupal_media_media';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_media_media` (
          `media_id` int(10) unsigned NOT NULL auto_increment,
          `name` varchar(100) NOT NULL,
          `parent` int(10) unsigned NOT NULL default '0',
          PRIMARY KEY  (`media_id`),
          KEY `parent` (`parent`)
        ) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1");
    }

}

	