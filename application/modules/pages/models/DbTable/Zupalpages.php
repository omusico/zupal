<?php

class Pages_Model_DbTable_Zupalpages extends Zupal_Table_Abstract
{

    protected $_name = 'zupal_pages';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ create_table @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function create_table () {
        $sql = <<<SQL_BLOCK
CREATE TABLE `zupal_pages` (
  `id` int(11) NOT NULL auto_increment,
  `atomic_id` int(11) NOT NULL,
  `resource` varchar(100) collate utf8_bin NOT NULL,
  `author` int(11) NOT NULL,
  `publish_status` varchar(45) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `atomic_id_2` (`atomic_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=31 ;
SQL_BLOCK;
        $this->getAdapter()->query($sql);
    }
}

