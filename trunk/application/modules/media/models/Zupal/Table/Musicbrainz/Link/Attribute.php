<?php

class Zupal_Table_Musicbrainz_Link_Attribute extends Zupal_Table_Abstract
{

    protected $_id_field = 'id';

    protected $_name = 'link_attribute';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `link_attribute` (
          `id` int(11) NOT NULL,
          `attribute_type` int(11) NOT NULL default '0',
          `link` int(11) NOT NULL default '0',
          `link_type` varchar(32) NOT NULL default '',
          PRIMARY KEY  (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1");
    }

    public function __construct()
    {
        parent::__construct(array("db" => Zupal_Module_Manager::getInstance()->database("musicbrainz")));
    }


}

	