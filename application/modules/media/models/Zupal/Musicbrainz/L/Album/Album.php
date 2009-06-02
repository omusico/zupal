<?php

class Zupal_Musicbrainz_L_Album_Album extends Zupal_Domain_Abstract
{

    protected static $_Instance = null;

    public function get($pID)
    {
        retun new self($pID);
    }

    public function tableClass()
    {
        return 'Zupal_Table_Musicbrainz_L_Album_Album';
    }

    public static function getInstance()
    {
        if (is_null(self::$_Instance)):
        	self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }


}

