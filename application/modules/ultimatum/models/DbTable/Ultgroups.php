<?php

class Ultimatum_Model_DbTable_Ultgroups extends Zupal_Table_Abstract
{

    protected $_name = 'ult_groups';

    public function create_table()
    {
        $sql = "CREATE TABLE `ult_groups` (
    `id` int(11) NOT NULL auto_increment,
    `atomic_id` int(11) NOT NULL,
    `resource` varchar(100) collate utf8_bin NOT NULL,
    `author` int(11) NOT NULL,
    `publish_status` varchar(45) collate utf8_bin NOT NULL,
    `active` TINYINT NOT NULL DEFAULT '1'
  PRIMARY KEY  (`id`),
  UNIQUE KEY `atomic_id_2` (`atomic_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=31 ;";
        $this->getAdapter()->query($sql);
    }


}

