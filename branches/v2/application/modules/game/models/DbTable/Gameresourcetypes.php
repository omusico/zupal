<?php

class Game_Model_DbTable_Gameresourcetypes extends Zupal_Table_Abstract
{

    protected $_name = 'game_resource_types';

    public function create_table()
    {
        $sql = <<<SQL
      CREATE TABLE `game_resource_types` (
  `id` int(11) NOT NULL,
  `game_type` int(11) NOT NULL,
  `atomic_id` int(11) NOT NULL,
  `cost` float(16,2) NOT NULL default '0.00',
  `score` int(11) NOT NULL default '0',
  `value_1` int(11) NOT NULL,
  `value_2` int(11) NOT NULL,
  `value_3` int(11) NOT NULL,
  `value_4` int(11) NOT NULL,
  `value_5` int(11) NOT NULL,
  `string_1` varchar(32) collate utf8_bin NOT NULL,
  `string_2` varchar(32) collate utf8_bin NOT NULL,
  `string_3` varchar(32) collate utf8_bin NOT NULL,
  `string_4` varchar(32) collate utf8_bin NOT NULL,
  `string_5` varchar(32) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='any "earnable/purchaseable" item in a game.';
SQL;
        $this->getAdapter()->query($sql);;
    }


}

