<?php

class ZUpal_Places_Postalcode
implements Zupal_Place_IItem
{

/* @@@@@@@@@@@@@@@@@@ constructor @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

public function __construct($pValue)
{
	$this->set_value($pValue);
}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ value @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_value = null;
	/**
	 * @return class;
	 */

	public function get_value() { return $this->_value; }

	public function set_value($pValue) { $this->_value = $pValue; }

	public function identity(){ return $this->get_value(); }

	public function __toString() { return $this->get_value(); }
}