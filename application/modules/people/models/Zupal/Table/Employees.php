<?php

class Zupal_Table_Employees extends Zupal_Table_Abstract
{

    protected $_id_field = 'eid';

    protected $_name = 'zupal_employees';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_employees` (
          `eid` int(11) NOT NULL auto_increment,
          `node_id` int(11) NOT NULL,
          `person_id` int(11) NOT NULL,
          `manager` int(11) NOT NULL,
          `position` varchar(100) NOT NULL,
          `hire_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
          `fire_date` timestamp NOT NULL default '0000-00-00 00:00:00',
          `salary` float NOT NULL,
          `status` varchar(100) NOT NULL,
          PRIMARY KEY  (`eid`)
        ) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1");
    }

    protected function _init()
    {
        if(!$this->table_exists()) $this->create_table();
    }


}

	