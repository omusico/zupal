<?php

class Ultimatum_Model_DbTable_Ultplayer extends Zupal_Table_Abstract
{

    protected $_name = 'ult_player';

    public function create_table()
    {
                $sql = <<<CT_SQL
CREATE TABLE `ult_player` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL,
  `game` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
CT_SQL;
        $this->getAdapter()->query($sql);;
    }


}

