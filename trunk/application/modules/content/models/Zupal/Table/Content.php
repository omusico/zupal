<?php

class Zupal_Table_Content extends Zupal_Table_Abstract
{

    protected $_id_field = 'id';

    protected $_name = 'zupal_content';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `zupal_content` (
          `id` int(10) unsigned NOT NULL auto_increment,
          `node_id` int(11) default NULL,
          `title` varchar(255) NOT NULL,
          `short_title` varchar(255) NOT NULL,
          `text` text NOT NULL,
          `raw_text` text NOT NULL,
          `author_id` int(11) NOT NULL,
          `publish_date` timestamp NULL default NULL,
          `unpublish_date` timestamp NULL default NULL,
          `is_public` tinyint(3) unsigned NOT NULL default '1',
          PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1");
    }

    protected function _init()
    {
        if(!$this->table_exists()) $this->create_table();
    }


}

	