<?php

class Zupal_Table_Musicbrainz_Lt_Album_Album extends Zupal_Table_Abstract
{

    protected $_id_field = 'id';

    protected $_name = 'lt_album_album';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `lt_album_album` (
          `id` int(11) NOT NULL,
          `parent` int(11) NOT NULL,
          `childorder` int(11) NOT NULL default '0',
          `mbid` char(36) NOT NULL,
          `name` varchar(255) NOT NULL,
          `description` text NOT NULL,
          `linkphrase` varchar(255) NOT NULL,
          `rlinkphrase` varchar(255) NOT NULL,
          `attribute` varchar(255) default '',
          `modpending` int(11) NOT NULL default '0',
          PRIMARY KEY  (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1");
    }

    public function __construct()
    {
        parent::__construct(array("db" => Zupal_Module_Manager::getInstance()->database("musicbrainz")));
    }


}

	