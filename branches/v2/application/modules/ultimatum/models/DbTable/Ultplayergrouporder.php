<?php

class Ultimatum_Model_DbTable_Ultplayergrouporder extends Zupal_Table_Abstract
{

    protected $_name = 'ult_player_group_order';

    public function create_table()
    {
        $sql = <<<SQL
CREATE TABLE `ult_player_group_order` (
  `id` int(11) NOT NULL auto_increment,
  `order` varchar(45) collate utf8_bin NOT NULL,
  `target` int(11) NOT NULL,
  `start_turn` int(11) NOT NULL,
  `end_turn` int(11) NOT NULL,
  `commander` int(11) NOT NULL,
  `given_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `interrupt_turn` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
SQL;
        $this->getAdapter()->query($sql);;
    }


}

