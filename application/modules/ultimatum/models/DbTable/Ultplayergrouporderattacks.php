<?php

class Ultimatum_Model_DbTable_Ultplayergrouporderattacks extends Zupal_Table_Abstract
{

    protected $_name = 'ult_player_group_order_attacks';

    public function create_table()
    {
        $sql = <<<SQL
CREATE TABLE `ult_player_group_order_attacks` (
  `id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL,
  `reduceprop` tinyint(4) NOT NULL,
  `reduceprpop_property` varchar(45) collate utf8_bin NOT NULL,
  `crush` tinyint(4) NOT NULL,
  `loss` TINYINT NOT NULL,
 `loss_strength` tinyint(4) NOT NULL,
  `loss_strength_count` int(11) NOT NULL,
  `payoff` tinyint(4) NOT NULL,
  `payoff_count` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
SQL;
        $this->getAdapter()->query($sql);;
    }


}

