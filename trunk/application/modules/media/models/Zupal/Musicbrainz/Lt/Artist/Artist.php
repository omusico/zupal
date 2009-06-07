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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ is_type @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pType_ID
	* @return <type>
	*/
	public function is_type ($pType_ID, $pInherit = TRUE)
	{
		if ($this->identity() == $pType_ID):
			return TRUE;
		elseif ($this->parent && $pInherit):
			return $this->parent()->is_type($pType_ID);
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ parent @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	private $_parent = NULL;
	function parent($pReload = FALSE)
	{
		if ($pReload || is_null($this->_parent)):
			if ($this->parent):
				$value = $this->get($this->parent);
			else:
				$value = FALSE;
			endif;
		// process
		$this->_parent = $value;
		endif;
		return $this->_parent;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ linkphrase @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param boolean $pRight_phrase = FALSE
	* @return string
	*/
	public function linkphrase ($pRight_phrase = FALSE)
	{

		$phrase = $pRight_phrase ? $this->rlinkphrase : $this->linkphrase;

		if (preg_match_all('~\{[^\}]+\}~', $phrase, $matches, PREG_PATTERN_ORDER)):
			foreach($matches[0] as $match):
				$phrase = str_replace($match, $this->decode($match), $phrase);
			endforeach;
		endif;

		return $phrase;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ attribute @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_attribute = NULL;

	function attrs($pReload = FALSE)
	{
		if ($pReload || is_null($this->_attribute)):
			$attr_table = Zupal_Musicbrainz_Link_Attribute::getInstance();
			$attrs = $attr_table->fetchAll(
				array(
					'link' => $this->identity(),
					'link_type' => 'artist_artist'
				)
			);

		// process
			$this->_attribute = $attrs;
		endif;
		return $this->_attribute;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ attr_types @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	private $_attr_types = NULL;
	function get_attr_types($pReload = FALSE)
	{
		if ($pReload || is_null($this->_attr_types)):		
			$anames = array();
			foreach($attrs as $attr):
				$names[] = $attr->get_type()->name();
			endforeach;		
		// process
			$this->_attr_types = $anames;
		endif;
		return $this->_attr_types;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ has_attr @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param string $pAttr
	* @return string
	*/
	public function has_attr ($pAttr)
	{
		return in_array($pAttr, $this->get_attr_types());
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ decode @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param string $pTerm
	* @return string
	*/
	public function decode ($pTerm)
	{
		if (preg_match('~\{(.*):(.*)\|(.*)\}~', $pTerm, $hits)):
			if ($this->has_attr($hits[1])):
				return $hits[2];
			else:
				return $hits[3];
			endif;
		endif;
	}

}

