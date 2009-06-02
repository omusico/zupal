<?php

class Zupal_Table_Musicbrainz_L_Artist_Artist extends Zupal_Table_Abstract
{

    protected $_id_field = 'id';

    protected $_name = 'l_artist_artist';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `l_artist_artist` (
          `id` int(11) NOT NULL,
          `link0` int(11) NOT NULL default '0',
          `link1` int(11) NOT NULL default '0',
          `link_type` int(11) NOT NULL default '0',
          `begindate` char(10) NOT NULL default '',
          `enddate` char(10) NOT NULL default '',
          `modpending` int(11) NOT NULL default '0',
          PRIMARY KEY  (`id`),
          KEY `link0` (`link0`),
          KEY `link1` (`link1`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1");
    }

    public function __construct()
    {
        parent::__construct(array("db" => Zupal_Module_Manager::getInstance()->database("musicbrainz")));
    }


}

	