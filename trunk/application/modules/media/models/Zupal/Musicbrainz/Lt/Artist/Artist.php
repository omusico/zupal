<?php

class Zupal_Musicbrainz_Lt_Artist_Artist extends Zupal_Domain_Abstract
{

    protected static $_Instance = null;

    public function get($pID)
    {
        return new self($pID);
    }

    public function tableClass()
    {
        return 'Zupal_Table_Musicbrainz_Lt_Artist_Artist';
    }

    public static function getInstance()
    {
        if (is_null(self::$_Instance)):
        	self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ linkphrase @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param boolean $pRight_phrase = FALSE
	* @return string
	*/
	public function linkphrase ($pRight_phrase = FALSE)
	{

		$phrase = $pRight_phrase ? $this->rlinkphrsae : $this->linkphrase;


	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ attribute @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_attribute = NULL;
	function get_attribute($pReload = FALSE)
	{
		if ($pReload || is_null($this->_attribute)):

			$value = 

		// process
		$this->_attribute = $value;
		endif;
		return $this->_attribute;
	}

}

