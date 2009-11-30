<?php

class Game_Model_DbTable_Gametypes extends Zupal_Table_Abstract
{

    protected $_name = 'game_types';

    public function create_table()
    {
        $sql = <<<SQL

CREATE TABLE `game_types` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `atomic_id` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `resource` varchar(45) collate utf8_bin NOT NULL COMMENT 'optinal resource required to play',
  `based_on` int(11) default NULL COMMENT 'optional -- "parent" type for a "mod"',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='a game' AUTO_INCREMENT=1 ;
SQL;
        $this->getAdapter()->query($sql);;
    }


}

