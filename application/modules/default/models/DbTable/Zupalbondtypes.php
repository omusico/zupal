<?php

class Model_DbTable_Zupalbondtypes extends Zupal_Table_Abstract
{

    protected $_name = 'zupal_bondtypes';

    public function create_table()
    {
        $sql = <<<SQL
CREATE TABLE `zupal_bondtypes` (
  `name` varchar(45) collate utf8_bin NOT NULL,
  `unique` tinyint(3) unsigned NOT NULL,
  `created_by_module` varchar(45) collate utf8_bin NOT NULL,
  `description` text collate utf8_bin NOT NULL,
  `ordered` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `zupal_bondtypes` VALUES('contains', 0, 'zupal', 0x4120225365742220636f6e7374727563742e2054686f7567682074686572652069732061206c6f63616c2068696572617263687920696e20636f6e7461696e6d656e742c207468657265206172652063697263756d7374616e636573206f6620226d757475616c20636f6e7461696e6d656e742220696e207265616c6974792e20, 1);
INSERT INTO `zupal_bondtypes` VALUES('parent', 1, 'zupal', 0x412073696e67756c6172207375706572696f7220656c656d656e74202d2d207468652067656e65736973206f662061202273696e676c6520706172656e74222074726565206f7220666f72657374206d6f64656c2e20, 1);
INSERT INTO `zupal_bondtypes` VALUES('network', 0, 'zupal', 0x41206e6f6e2d68656972617263686963616c206e6574776f726b2c2073756368206173206120667269656e64732f736f6369616c206e6574776f726b2e20, 0);
SQL;
        $this->getAdapter()->query($sql);;
    }


}

