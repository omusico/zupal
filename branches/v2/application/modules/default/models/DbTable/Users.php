<?php

class Model_DbTable_Users
extends Zupal_Table_Abstract
{
    protected $_name = 'zupal_users';

    const CREATE_SQL = "CREATE TABLE `zupal_users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(45) collate utf8_bin NOT NULL,
  `password` varchar(45) collate utf8_bin NOT NULL,
  `passwordmd5` varchar(200) collate utf8_bin NOT NULL,
  `nid` int(10) unsigned NOT NULL,
  `vid` int(10) unsigned NOT NULL,
  `role` int(11) NOT NULL,
  `status` set('active','validated','obsolete','deleted','banned','duplicate') collate utf8_bin NOT NULL default 'active',
  PRIMARY KEY  (`id`),
  KEY `username` (`username`),
  KEY `user_passwordmp5` (`username`,`passwordmd5`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;";

    const DEFAULT_PASSWORD = 'Adm1n_pa$$word';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ create_table @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function create_table () {
        $adapter = Zupal_Database_Manager::get_adapter();
        $adapter->query(self::CREATE_SQL);

        $admin = $this->fetchNew();
        $admin->username = 'admin';
        $admin->role = Model_Roles::ROLE_ADMIN;
        $admin->password = $admin->make_password(self::DEFAULT_PASSWORD);
        $admin->save();
    }

}