<?php

class Zupal_Modules extends Zupal_Domain_Abstract
{

    protected static $_Instance = null;

    public function get($pID)
    {
        return new self($pID);
    }

    public function tableClass()
    {
        return 'Zupal_Table_Modules';
    }

    public static function getInstance()
    {
        if (is_null(self::$_Instance)):
        	self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

	public static function module($pName){
		$pName = strtolower($pName);
		return self::getInstance()->get($pName);
	}

}

