<?php

class Ultimatum_Model_DbTable_Ultplayergrouporderresizes extends Zupal_Table_Abstract
{

    protected $_name = 'ult_player_group_order_resizes';

    public function create_table()
    {
        $sql = <<<SQL
CREATE TABLE `ult_player_group_order_resizes` (
  `id` int(11) NOT NULL auto_increment,
  `network` int(11) NOT NULL,
  `growth` int(11) NOT NULL,
  `offense` int(11) NOT NULL,
  `defense` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
SQL;
        $this->getAdapter()->query($sql);;
    }


}

