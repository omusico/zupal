<?

class Zupal_Image
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __construct @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pPath, $pWidth=0, $pHeight=0
	* @return <type>
	*/
	public function __construct ($pPath, $pWidth=0, $pHeight=0)
	{
		$this->set_path($pPath);
		$this->set_height($pHeight);
		$this->set_width($pWidth);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ path @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_path = null;
	/**
	 * @return class;
	 */

	public function get_path() { return $this->_path; }

	public function set_path($pValue) { $this->_path = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ height @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_height = null;
	/**
	 * @return string | int;
	 */

	public function get_height($pAs_Tag = FALSE) {
		if ($pAs_Tag):
			if ($this->_height):
				return sprintf(' height="%s" ', $this->_height);
			else:
				return '';
			endif;
		else:
			return $this->_height;
		endif;
	}

	public function set_height($pValue) { $this->_height = max(0, (int) $pValue); }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ width @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_width = null;
	/**
	 * @return string | int;
	 */

	public function get_width($pAs_Tag = FALSE) {
		if ($pAs_Tag):
			if ($this->_width):
				return sprintf(' width="%s" ', $this->_width);
			else:
				return '';
			endif;
		else:
			return $this->_width;
		endif;
	}

	public function set_width($pValue) { $this->_width = max(0, (int) $pValue); }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __toString @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	const IMG = '<img src="%s" %s %s border="0" />';
	/**
	*
	* @return string
	*/
	public function __toString ()
	{
		return sprintf(self::IMG, $this->get_path(), $this->get_height(TRUE), $this->get_width(TRUE));
	}
}