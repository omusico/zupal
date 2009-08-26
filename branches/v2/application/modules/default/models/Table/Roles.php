<?php

class Model_Table_Roles
extends Zupal_Table_Abstract
{
    protected $_name = 'zupal_users';

    const CREATE_SQL = " CREATE TABLE `zupal2`.`zupal_roles` (
`role_id` VARCHAR( 45 ) NOT NULL ,
`title` VARCHAR( 100 ) NOT NULL ,
`notes` TEXT NOT NULL ,
PRIMARY KEY ( `role_id` )
) ENGINE = MYISAM ";

    const ANON_ROLE = "INSERT INTO `zupal2`.`zupal_roles` (
`role_id` ,
`title` ,
`notes`
)
VALUES (
'anonymous', 'Anonymous User', 'The minimal role for people viewing the site without logging in. Privileges for this role will apply to anyone.'
);";
    
    const DEFAULT_PASSWORD = 'Adm1n_pa$$word';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ create_table @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function create_table () {
        $adapter = Zupal_Database_Manager::get_adapter();
        $adapter->query(self::CREATE_SQL);
        $adapter->query(self::ANON_ROLE);

        $admin = $this->fetchNew();
        $admin->username = 'admin';
        $admin->role = Model_Roles::ROLE_ADMIN;
        $admin->password = $admin->make_password(self::DEFAULT_PASSWORD);
        $admin->save();
    }

}