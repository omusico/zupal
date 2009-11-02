<?php

class Model_DbTable_Zupalroutes extends Zupal_Table_Abstract
{

    protected $_name = 'zupal_routes';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ create_table @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function create_table () {
        $sql = <<<SQL
        CREATE TABLE `zupal_routes` (
  `route_id` varchar(45) collate utf8_bin NOT NULL,
  `route_path` varchar(45) collate utf8_bin NOT NULL,
  `module` varchar(25) collate utf8_bin NOT NULL,
  `controller` varchar(25) collate utf8_bin NOT NULL,
  `action` varchar(25) collate utf8_bin NOT NULL,
  `created_by_module` varchar(25) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`route_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
SQL;

        $this->getAdapter()->query($sql);
    }
}

