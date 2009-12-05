<?php

class Game_Model_DbTable_Gamesessions extends Zupal_Table_Abstract
{

    protected $_name = 'game_sessions';

    public function create_table()
    {
        $sql = <<<SQL
CREATE TABLE `game_sessions` (
  `id` int(10) unsigned NOT NULL,
  `game_type` int(11) NOT NULL,
  `session_title` varchar(45) collate utf8_bin NOT NULL,
  `game_template` int(11) NOT NULL default '1' COMMENT 'optional key for any optional play mode/setting/etc.',
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `max_players` int(10) unsigned NOT NULL COMMENT '0 means unlimited',
  `current_turn` int(10) unsigned NOT NULL,
  `max_turns` int(11) NOT NULL,
  `max_score` int(11) NOT NULL,
  `difficulty` int(11) NOT NULL,
  `notes` text collate utf8_bin NOT NULL,
  `active` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
SQL;
        $this->getAdapter()->query($sql);;
    }


}

