<?php

class Ultimatum_Model_DbTable_Ultgames extends Zupal_Table_Abstract
{

    protected $_name = 'ult_games';

    public function create_table()
    {
        $sql = "CREATE TABLE `ult_games` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(45) collate utf8_bin NOT NULL,
  `started_on` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `status` enum('started','over') collate utf8_bin NOT NULL default 'started',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;";
        $this->getAdapter()->query($sql);;
    }


}

