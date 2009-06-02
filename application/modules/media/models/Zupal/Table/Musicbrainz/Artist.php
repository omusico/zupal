<?php

class Zupal_Table_Musicbrainz_Artist extends Zupal_Table_Abstract
{

    protected $_id_field = 'id';

    protected $_name = 'artist';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `artist` (
          `id` int(11) NOT NULL,
          `name` varchar(255) NOT NULL,
          `gid` char(36) NOT NULL,
          `modpending` int(11) default '0',
          `sortname` varchar(255) NOT NULL,
          `page` int(11) NOT NULL,
          `resolution` varchar(64) default NULL,
          `begindate` char(10) default NULL,
          `enddate` char(10) default NULL,
          `type` smallint(6) default NULL,
          `quality` smallint(6) default '-1',
          `modpending_qual` int(11) default '0',
          PRIMARY KEY  (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1");
    }

    public function __construct()
    {
        parent::__construct(array("db" => Zupal_Module_Manager::getInstance()->database("musicbrainz")));
    }


}

	