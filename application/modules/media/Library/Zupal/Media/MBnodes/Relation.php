<?php

class Zupal_Media_MBnodes_Relation
{
	public function __construct($pArtist = NULL, $pTarget = NULL, $pType = '', $pName = '') {
		$this->set_artist($pArtist);
		$this->set_target($pTarget);
		$this->set_type($pType);
		$this->set_name($pName);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ meta @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	 *
	* @return <type>
	*/
	public function meta ()
	{
		if (!strcasecmp($this->get_type(), 'release')):
			return Zupal_Media_MusicBrains::get_release($this->get_target());
		endif;
		return '';
	}

	
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
	
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ relationship @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	private $_relationship = '';
	/**
	  * @return scalar
	  */
	public function get_relationship(){ return $this->_relationship; }
	public function set_relationship($value){ $this->_relationship = strtolower((string) $value); }
	
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ name @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_name = null;
	/**
	 * @return class;
	 */

	public function get_name() { return $this->_name; }

	public function set_name($pValue) { $this->_name = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ id @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_target = null;
	/**
	 * @return class;
	 */

	public function get_target() { return $this->_target; }

	public function set_target($pValue) {
		$this->_target = $pValue;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ type @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_type = null;
	/**
	 * @return class;
	 */

	public function get_type() { return $this->_type; }

	public function set_type($pValue) { $this->_type = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ relation @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_relation = array();
	/**
	 *
	 * @param scalar $pFrom
	 * @param scalar $pTarget
	 * @return Zupal_Media_MBnodes_Relation
	 */
	public static function factory($pFrom, $pTarget)
	{
		if ($pFrom instanceof Zupal_Media_MBnodes_Artist):
			$pFrom = $pFrom->get_id();
		endif;
		if (array_key_exists($pFrom, self::$_relation)):
			if (array_key_exists($pTarget, self::$_relation[$pFrom])):
				return self::$_relation[$pFrom][$pTarget];
			else:
				self::$_relation[$pFrom][$pTarget] = new Zupal_Media_MBnodes_Relation($pFrom, $pTarget);
			endif;
		else:
			self::$_relation[$pFrom][$pTarget] = new Zupal_Media_MBnodes_Relation($pFrom, $pTarget);
		endif;
		
		return self::$_relation[$pFrom][$pTarget];
	}
}