<?php

class Zupal_Table_Musicbrainz_Release extends Zupal_Table_Abstract
{

    protected $_id_field = 'id';

    protected $_name = 'release';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `release` (
          `id` int(11) NOT NULL,
          `album` int(11) NOT NULL,
          `country` int(11) NOT NULL,
          `releasedate` char(10) NOT NULL,
          `modpending` int(11) default '0',
          `label` int(11) default NULL,
          `catno` varchar(255) default NULL,
          `barcode` varchar(255) default NULL,
          `format` smallint(6) default NULL,
          PRIMARY KEY  (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1");
    }

    public function __construct()
    {
        parent::__construct(array("db" => Zupal_Module_Manager::getInstance()->database("musicbrainz")));
    }


}

	