<?php

class Ultimatum_Model_DbTable_Ultplayergrouporders extends Zupal_Table_Abstract
{

    protected $_name = 'ult_player_group_orders';

    public function create_table()
    {
        $sql = <<<SQL
CREATE TABLE `ult_player_group_orders` (
  `id` int(11) NOT NULL auto_increment,
  `player_group` int(11) NOT NULL,
  `type` varchar(45) collate utf8_bin NOT NULL,
  `repeat` varchar(45) collate utf8_bin NOT NULL,
  `target` int(11) NOT NULL,
  `series` int(11) NOT NULL,
  `commander` int(11) NOT NULL,
  `given_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `active` tinyint(4) NOT NULL default '1',
  `status` enum('pending','executing','complete','cancelled') collate utf8_bin NOT NULL default 'pending',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=42;
SQL;
        $this->getAdapter()->query($sql);;
    }


}

