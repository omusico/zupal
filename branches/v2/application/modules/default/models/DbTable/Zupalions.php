<?php

class Model_DbTable_Zupalions extends Zupal_Table_Abstract
{

    protected $_name = 'zupal_ions';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ create_table @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void;
     */
    public function create_table () {
        $sql = <<<SQL
CREATE TABLE `zupal_ions` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `atomic_id` int(11) NOT NULL,
  `name` varchar(90) collate utf8_bin NOT NULL,
  `value` varchar(255) collate utf8_bin NOT NULL,
  `rank` int(11) NOT NULL,
  `version` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
SQL;

    }
}

