<?php

class Ultimatum_Model_DbTable_Ultplayergroups extends Zupal_Table_Abstract
{

    protected $_name = 'ult_player_groups';

    public function create_table()
    {
        $sql = <<<SQL

CREATE TABLE `ult_player_groups` (
  `id` int(11) NOT NULL,
  `game` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `controlling_group` int(11) default NULL,
  `player` int(11) NOT NULL,
  `on_turn` int(11) NOT NULL,
  `offense` tinyint(3) unsigned NOT NULL,
  `defense` tinyint(3) unsigned NOT NULL,
  `network` tinyint(3) unsigned NOT NULL,
  `growth` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
SQL;
        $this->getAdapter()->query($sql);;
    }


}

