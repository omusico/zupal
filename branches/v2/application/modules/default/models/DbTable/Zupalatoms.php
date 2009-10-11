<?php

class Model_DbTable_Zupalatoms extends Zupal_Table_Abstract
{

    protected $_name = 'zupal_atoms';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ create_table @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function create_table () {
        $sql = <<<SQL_BLOCK
CREATE TABLE `zupal_atoms` (
  `id` int(11) NOT NULL auto_increment,
  `atomic_id` int(11) NOT NULL,
  `version` int(11) NOT NULL,
  `lead` text collate utf8_bin NOT NULL,
  `title` varchar(255) collate utf8_bin NOT NULL,
  `content` text collate utf8_bin NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `author` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `zupal_atoms`
--

INSERT INTO `zupal_atoms` VALUES(1, 1, 1, 0x57656c636f6d6520546f204d792053697465, 'Home Page', '', '2009-10-10 17:28:56', 0, 100);
SQL_BLOCK;
        $this->getAdapter()->query($sql);
    }
}

