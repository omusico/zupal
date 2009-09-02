<?php

class Model_DbTable_Roles
extends Zupal_Table_Abstract
{
    protected $_name = 'zupal_roles';
    protected $_id_field = 'role_id';
    
    const CREATE_SQL = "CREATE TABLE `zupal_roles` (
  `role_id` varchar(45) collate utf8_bin NOT NULL,
  `title` varchar(100) collate utf8_bin NOT NULL,
  `notes` text collate utf8_bin NOT NULL,
  `rank` tinyint(4) NOT NULL,
  `module` varchar(45) collate utf8_bin NOT NULL default 'zupal',
  PRIMARY KEY  (`role_id`),
  FULLTEXT KEY `notes` (`notes`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

    const ZUPAL_ROLES = "
INSERT INTO `zupal_roles` VALUES('anonymous', 'Anonymous User', 0x546865206d696e696d616c20726f6c6520666f722070656f706c652076696577696e6720746865207369746520776974686f7574206c6f6767696e6720696e2e2050726976696c6567657320666f72207468697320726f6c652077696c6c206170706c7920746f20616e796f6e652e, 0, 'zupal');
INSERT INTO `zupal_roles` VALUES('unvalidated', 'Unvalidated User', 0x4120757365722077686f736520656d61696c206164647265737320686173206e6f74206265656e20636f6f62657261746564, 1, 'zupal');
INSERT INTO `zupal_roles` VALUES('validted', 'Validated User', 0x4120757365722077686f736520656d61696c206164647265737320686173206265656e2076616c696461746564, 2, 'zupal');
INSERT INTO `zupal_roles` VALUES('admin', 'Administrator', 0x41207573657220776974682061646d696e6973747261746976652070726976696c65676573, 127, 'zupal');
INSERT INTO `zupal_roles` VALUES('editor', 'Editor', 0x412070726976696c656765642075736572207769746820746865206162696c69747920746f206d616e61676520636f6e74656e742c20627574206e6f742074686520656e7469726520736974652e20, 100, 'zupal');
";
    
    const DEFAULT_PASSWORD = 'Adm1n_pa$$word';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ create_table @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function create_table () {
        $adapter = Zupal_Database_Manager::get_adapter();
        $adapter->query(self::CREATE_SQL);
        $adapter->query(self::ZUPAL_ROLES);
    }

}