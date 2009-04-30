<?php
/*
 * This may not be employed. 
 */

class Zupal_Place_Postalcode
{

	public function identity() { return $this->get_value(); }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ value @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_value = null;
	/**
	 * @return class;
	 */

	public function get_value() { return $this->_value; }

	public function set_value($pValue) { $this->_value = $pValue; }



	public function __toString() { return $this->get_value(); }
}