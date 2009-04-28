<?php

/**
 * this class represents the domain class for the nodes table.
 * as such it does NOT implement Zupal_Node_INode, but it is a component
 * of any domain that implements Zupal_Node_INode.
 */

class Zupal_Places Extends Zupal_Domain_Abstract
Implements IPlace
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
	 * @return Zupal_Place_IItem
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
		else:
			$this->city = $pCity;
		endif;
	}

	/**
	 * @return Zupal_Place_IItem
	 */
	public function getState(){
		return Zupal_Places_States::get_city($this);
	}

	public function setState($pState)
	{
		if ($pState instanceof Zupal_Places_States):
			$this->city_id = $pState->identity();
			$this->city_name = $pState->get_value();
			if ($pState->getCountry()):
				$this->setCountry($pState->getCountry());
			endif;
		elseif(is_numeric($pState)):
			$city = Zupal_Places_States::getInstance()->find($pState);
			if ($city):
				$this->city_id = $city->identity();
				$this->city = $city->get_name();
			endif;
		else:
			$this->city = $pState;
		endif;
	}

	/**
	 * @reutrn Zupal_Place_IItem
	 */
	public function getPostal();
	public function setPostal($pPostal);

	/**
	 *
	 * @param string $pSeperator
	 */
	public function block($pSeparator = "\n");

	/**
	 * @return IPlace[]
	 */
	public function findLikeMe();

	/**
	 * @return IPlace[]
	 */
	public function findNearMe($pKilometers, $pMax = NULL);

}


