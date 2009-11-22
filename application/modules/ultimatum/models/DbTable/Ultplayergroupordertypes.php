<?php

class Ultimatum_Model_DbTable_Ultplayergroupordertypes extends Zupal_Table_Abstract
{

    protected $_name = 'ult_player_group_order_types';
    protected $_id_field = 'name';

    public function create_table()
    {
        $sql = <<<SQL
CREATE TABLE `ult_player_group_order_types` (
  `name` varchar(45) collate utf8_bin NOT NULL,
  `title` varchar(255) collate utf8_bin NOT NULL,
  `description` text collate utf8_bin NOT NULL,
  `target_type` enum('self','other','both','none') collate utf8_bin NOT NULL default 'other',
  `turns` tinyint(11) unsigned NOT NULL default '1',
  PRIMARY KEY  (`name`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
SQL;
        $this->getAdapter()->query($sql);;
    }


}

