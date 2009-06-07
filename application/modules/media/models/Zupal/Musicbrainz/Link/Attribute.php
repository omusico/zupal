<?php

class Zupal_Musicbrainz_Link_Attribute extends Zupal_Domain_Abstract
{

    protected static $_Instance = null;

    public function get($pID)
    {
        return new self($pID);
    }

    public function tableClass()
    {
        return 'Zupal_Table_Musicbrainz_Link_Attribute';
    }
/**
 *
 * @return Zupal_Musicbrainz_Link_Attribute
 */
    public static function getInstance()
    {
        if (is_null(self::$_Instance)):
        	self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ type @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_type = NULL;
	function get_type($pReload = FALSE)
	{
		if ($pReload || is_null($this->_type)):
			$t = Zupal_Musicbrainz_Link_Attribute_Type::getInstance();
			$value = $t->get($this->attribute_type);
			// process
			$this->_type = $value;
		endif;
		return $this->_type;
	}

}

