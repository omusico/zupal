<?php

class Ultimatum_Model_DbTable_Ultplayergroupsize extends Zupal_Table_Abstract
{

    protected $_name = 'ult_player_group_size';

    public function create_table()
    {
        $sql = <<<SQL
CREATE TABLE `ult_player_group_size` (
  `id` int(11) NOT NULL auto_increment,
  `game` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `notes` varchar(255) collate utf8_bin NOT NULL,
  `activity` enum('offense','defense','growth','network') collate utf8_bin NOT NULL default 'growth',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
SQL;
        $this->getAdapter()->query($sql);;
    }


}

