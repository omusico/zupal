<?php

class Ultimatum_Model_DbTable_Ultgamegroups extends Zupal_Table_Abstract
{

    protected $_name = 'ult_game_groups';

    public function create_table()
    {
        $sql = <<<SQL
CREATE TABLE `ult_game_groups` (
  `id` int(11) NOT NULL auto_increment,
  `game` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `atomic_id` int(11) NOT NULL,
  `controlling_group` int(11) NOT NULL default '0',
  `player` int(11) NOT NULL,
  `on_turn` int(11) NOT NULL,
  `offense` tinyint(3) unsigned NOT NULL,
  `defense` tinyint(3) unsigned NOT NULL,
  `network` tinyint(3) unsigned NOT NULL,
  `growth` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;
SQL;
        $this->getAdapter()->query($sql);;
    }


}

