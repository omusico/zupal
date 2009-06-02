<?php

class Zupal_Table_Musicbrainz_Artist_Relation extends Zupal_Table_Abstract
{

    protected $_id_field = 'id';

    protected $_name = 'artist_relation';

    public function create_table()
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->query("CREATE TABLE `artist_relation` (
          `id` int(11) NOT NULL,
          `artist` int(11) NOT NULL,
          `ref` int(11) NOT NULL,
          `weight` int(11) NOT NULL,
          PRIMARY KEY  (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1");
    }

    public function __construct()
    {
        parent::__construct(array("db" => Zupal_Module_Manager::getInstance()->database("musicbrainz")));
    }


}

	