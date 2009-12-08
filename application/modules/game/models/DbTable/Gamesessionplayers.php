<?php

class Game_Model_DbTable_Gamesessionplayers extends Zupal_Table_Abstract
{

    protected $_name = 'game_session_players';

    public function create_table()
    {
        $sql = <<<SQL
        CREATE TABLE `game_session_players` (
  `id` int(11) NOT NULL auto_increment,
  `game_session` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `handle` varchar(45) collate utf8_bin NOT NULL COMMENT 'optional pseudname of "character" within game',
  `active` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='A join between a particular game session and a user' AUTO_INCREMENT=1 ;
SQL;
        $this->getAdapter()->query($sql);;
    }


}

