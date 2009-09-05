<?php

class Model_DbTable_Acl extends Zupal_Table_Abstract
{

    protected $_name = 'zupal_acl';

    const CREATE_TABLE = 'CREATE TABLE `zupal_acl` (
  `id` int(11) NOT NULL auto_increment,
  `resource` varchar(45) collate utf8_bin NOT NULL,
  `role` varchar(45) collate utf8_bin NOT NULL,
  `allow` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ create @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function create_table() {
        $this->getAdapter()->query(self::CREATE_TABLE);
    }
}

