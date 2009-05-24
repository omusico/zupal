<?php

class Zupal_Media_MBnodes_Release
{
	public function __construct($pID) {
		$this->set_id($pID);
		self::$_releases[$pID] = $this;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ id @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_id = null;
	/**
	 * @return class;
	 */

	public function get_id() { return $this->_id; }

	public function set_id($pValue) { $this->_id = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ end @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_end = NULL;
	/**
	  * @return scalar
	  */
	public function get_end(){ return $this->_end; }
	public function set_end($value){ $this->_end = $value; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ begin @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_begin = NULL;
	/**
	  * @return scalar
	  */
	public function get_begin(){ return $this->_begin; }
	public function set_begin($value){ $this->_begin = strtolower((string) $value); }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ artist @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_artist = null;
	/**
	 * @return class;
	 */

	public function get_artist() { return $this->_artist; }

	public function set_artist($pValue) {
		if ($pValue):
			if (!is_scalar($pValue)):
				$pValue = $pValue->get_id();
			endif;
			$this->_artist = $pValue;
		else:
			$this->_artist = NULL;
		endif;
	}
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ relations @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_relations = array();

	public function add_relation($pValue)
	{
		$this->_relations[] = $pValue;
	}

	public function get_relation($pID){ return $this->_relations[$pID]; }

	public function get_relations(){ return $this->_relations; }
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ title @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_title = null;
	/**
	 * @return class;
	 */

	public function get_title() { return $this->_title; }

	public function set_title($pValue) { $this->_title = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ type @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_type = null;
	/**
	 * @return class;
	 */

	public function get_type() { return $this->_type; }

	public function set_type($pValue) { $this->_type = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ releases @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_releases = array();
	/**
	 *
	 * @param scalar $pID
	 * @return Zupal_Media_MBnodes_Release
	 */
	public static function factory($pID)
	{
		if (!array_key_exists($pID, self::$_releases)):
			$release = new Zupal_Media_MBnodes_Release($pID);
			self::$_releases[$pID] = $release;
		endif;
		return self::$_releases[$pID];
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __toString @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public function __toString()
	{
		$out = $this->get_type() . ' &quot;' . $this->get_title() . '&quot;<br />';
		foreach($this->get_relations() as $relation): 
			$out .= $relation . '<br />';
		endforeach;
		
		return $out;
	}
}