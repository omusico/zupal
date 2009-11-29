<?php

class Ultimatum_Model_DbTable_Ultgamegroupscans extends Zupal_Table_Abstract
{

    protected $_name = 'ult_game_group_scans';

    public function create_table()
    {
        $sql = <<<SQL
CREATE TABLE `ult_game_group_scans` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `player_group_id` int(11) NOT NULL,
  `target_group_id` int(11) NOT NULL,
  `scanned_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `on_turn` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
SQL;
        $this->getAdapter()->query($sql);;
    }


}

