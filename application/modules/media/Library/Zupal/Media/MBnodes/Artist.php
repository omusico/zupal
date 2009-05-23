<?

class Zupal_Media_MBnodes_Artist
{

	public function __construct($pID) {
		$this->set_id($pID);
		self::$_artists[$pID] = $this;
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ id @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_id = null;
	/**
	 * @return class;
	 */

	public function get_id() { return $this->_id; }

	public function set_id($pValue) { $this->_id = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ name @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_name = null;
	/**
	 * @return class;
	 */

	public function get_name() { return $this->_name; }

	public function set_name($pValue) { $this->_name = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ born @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_born = null;
	/**
	 * @return class;
	 */

	public function get_born() { return $this->_born; }

	public function set_born($pValue) { $this->_born = $pValue; }
		
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ died @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_died = null;
	/**
	 * @return class;
	 */

	public function get_died() { return $this->_died; }

	public function set_died($pValue) { $this->_died = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ type @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_type = null;
	/**
	 * @return class;
	 */

	public function get_type() { return $this->_type; }

	public function set_type($pValue) { $this->_type = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ relations @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_relations = array();

	public function add_relation($pValue)
	{
		$this->_relations[] = $pValue;
	}

	public function get_relation($pID){ return $this->_relations[$pID]; }

	public function get_relations(){ return $this->_relations; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ artists @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_artists = array();
	/**
	 *
	 * @param scalar $pID
	 * @return Zupal_Media_MBnodes_Artist
	 */
	public static function factory($pID)
	{
		if (!array_key_exists($pID, self::$_artists)):
			$artist = new Zupal_Media_MBnodes_Artist($pID);
			self::$_artists[$pID] = $artist;
		endif;
		return self::$_artists[$pID];
	}

}

