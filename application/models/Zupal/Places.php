<?php

/**
 * this class represents the domain class for the nodes table.
 * as such it does NOT implement Zupal_Node_INode, but it is a component
 * of any domain that implements Zupal_Node_INode.
 */

class Zupal_Places Extends Zupal_Domain_Abstract
Implements Zupal_Place_IPlace
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * @see CPF_Formset_Domain::get_table_class()
	 *
	 */
	public function tableClass ()
	{
		return preg_replace('~^Zupal_~', 'Zupal_Table_', get_class($this));
	}

	/**
	 * @see CPF_Formset_Domain::get()
	 *
	 * @param unknown_type $pID
	 * @return CPF_Formset_Domain
	 */
	public function get ($pID)
	{
		return new Zupal_Places($pID);
	}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ name @@@@@@@@@@@@@@@@@@@@@@@@ */
	
	/**
	 * @return class;
	 */

	public function get_name() { return $this->name; }

	public function set_name($pValue) { $this->name = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_Instance = NULL;
	/**
	 *
	 * @param boolean $pReload
	 * @return Zupal_Places
	 */
	static function getInstance($pReload = FALSE)
	{
		if ($pReload || is_null(self::$_Instance)):
		// process
		self::$_Instance = new Zupal_Places(Zupal_Domain_Abstract::STUB);
		endif;
		return self::$_Instance;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ place interface @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * @returns a stdClass with a lat and long prop
	 */
	public function getCoords()
	{
		$s = new StdClass();
		$s->lat = $this->lat;
		$s->long = $this->long;
		return $s;
	}
	
	/**
	 * an array, class with lat/long params, or a pair of floats.
	 */
	public function setCoords($pVal1, $pVal2 = NULL)
	{
		if (is_object($pVal1)):
			$this->lat = $pVal1->lat;
			$this->long = $pVal1->long;
		elseif (is_array($pVal1)):
			list($this->lat, $this->long) = $pVal1;
		elseif (is_numeric($pVal1)):
			$this->lat = $pVal1;
			$this->long = $pVal2;
		else:
			$args = func_get_args();
			$args = print_r($args, 1);
			throw new Exception(__METHOD__ . ': bad args passed ' . $args);
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ address @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	/**
	 * @return Zupal_Place_Address
	 */
	public function getAddress()
	{
		return new Zupal_Place_Address($this->addr, $this->addr2);
	}
	public function setAddress($pAddress)
	{
		if ($pAddress instanceof Zupal_Place_Address)
		{
			$this->addr = $pAddress->address;
			$this->addr2 = $pAddress->address2;
		}
		elseif (is_array($pAddress))
		{
			$this->addr = array_shift($pAddress);
			$this->addr2 = array_shift($pAddress);
		}
	}

	/**
	 * @return Zupal_Place_IItem
	 */
	public function getCity(){
		return Zupal_Places_Cities::get_city($this);
	}

	public function setCity($pCity)
	{
		if ($pCity instanceof Zupal_Places_Cities):
			$this->city_id = $pCity->identity();
			$this->city_name = $pCity->get_value();


		elseif(is_numeric($pCity)):
			$city = Zupal_Places_Cities::getInstance()->find($pCity);
			if ($city):
				$this->city_id = $city->identity();
				$this->city = $city->get_name();
			endif;
			//@TODO: else throw?
		else:
			$this->city = $pCity;
		endif;
	}

	/**
	 * @return Zupal_Place_IItem
	 */
	public function getState(){
		return Zupal_Places_States::get_state($this);
	}

	public function setState($pState)
	{
		if ($pState instanceof Zupal_Places_States):
			$this->state_id = $pState->identity();
			$this->state_name = $pState->get_value();
			if ($pState->getCountry()):
				$this->setCountry($pState->getCountry());
			endif;
		elseif(is_numeric($pState)):
			$state = Zupal_Places_States::getInstance()->find($pState);
			if ($state):
				$this->state_id = $state->identity();
				$this->state = $state->get_name();
			endif;
		else:
			$this->state = $pState;
		endif;
	}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ country @@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * @return class;
	 */

	public function getCountry() {
		return Zupal_Places_Countries::getInstance()->get($this->country_id);
	}

	public function setCountry($pValue) {
		$country = Zupal_Places_Countries::getInstance()->get_country($pValue);
		if ($country):
			$this->country_id = $country->identity();
			$this->country = $country->get_value();
		endif;
	}



/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ postal	 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function getPostalcode(){ return $this->postalcode; 	}
	public function setPostalcode($pPostal){ $this->postalcode = $pPostal; }

	/**
	 *
	 * @param string $pSeperator
	 */
	public function block($pSeparator = "\n")
	{
		return join($pSeparator, array($this->get_name(), $this->getAddress(), $this->getCity(), $this->getState(), $this->getCountry(), $this->getPostal()));
	}

	/**
	 * @return IPlace[]
	 */
	public function findLikeMe(){
		 //@TODO; 
	}

	/**
	 * @return IPlace[]
	 */
	public function findNearMe($pKilometers, $pMax = NULL)
	{
		//@TODO;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function save ()
	{
		$this->state_id = $this->getState()->identity();
		$this->city_id  = $this->getCity()->identity();
		parent::save();
	}
	
}


