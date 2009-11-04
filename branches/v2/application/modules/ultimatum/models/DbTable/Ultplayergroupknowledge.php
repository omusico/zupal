<?php

class Ultimatum_Model_DbTable_Ultplayergroupknowledge extends Zupal_Table_Abstract
{

    protected $_name = 'ult_player_group_knowledge';

    public function create_table()
    {
        $sql = <<<SQLTXT
CREATE TABLE `ult_player_group_knowledge` (
  `id` int(11) NOT NULL auto_increment,
  `player` int(11) NOT NULL,
  `group` int(11) NOT NULL,
  `group_player` int(11) default NULL,
  `last_update` int(11) NOT NULL,
  `size` int(11) default NULL,
  `offense_size` int(10) unsigned default NULL,
  `defense_size` int(10) unsigned default NULL,
  `network_size` int(10) unsigned default NULL,
  `growth_size` int(10) unsigned default NULL,
  `group_offense` tinyint(4) default NULL,
  `group_defense` tinyint(4) default NULL,
  `group_network` tinyint(4) default NULL,
  `group_growth` tinyint(4) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=86 ;
SQLTXT;
        $this->getAdapter()->query($sql);;
    }


}

