<?php

class Zupal_Table_Media_Musicbrains_Relations extends Zupal_Table_Abstract
{

    protected $_id_field = 'id';

    protected $_name = 'zupal_media_musicbrains_relations';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_media_musicbrains_relations` (
          `id` int(11) NOT NULL auto_increment,
          `label` varchar(255) NOT NULL,
          `from` varchar(255) NOT NULL,
          `from_type` varchar(20) NOT NULL,
          `target` varchar(255) NOT NULL,
          `target_type` varchar(20) NOT NULL,
          `type` varchar(20) NOT NULL,
          `relationship` varchar(20) NOT NULL,
          PRIMARY KEY  (`id`),
          KEY `from` (`from`),
          KEY `from_type` (`from_type`),
          KEY `to` (`target`),
          KEY `to_type` (`target_type`)
        ) ENGINE=MyISAM AUTO_INCREMENT=561 DEFAULT CHARSET=latin1");
    }

}

	