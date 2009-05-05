<?php

class Zupal_Places_Cities
extends Zupal_Domain_Abstract
implements Zupal_Place_IItem
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
		return new Zupal_Places_Cities($pID);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ value @@@@@@@@@@@@@@@@@@@@@@@@ */

	public function get_value() { return $this->get_name(); }

	public function set_value($pValue) { $this->set_name($pValue); }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ name @@@@@@@@@@@@@@@@@@@@@@@@ */

	public function get_name() { return $this->name; }

	public function set_name($pValue) { $this->name = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __toString @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	* @return string
	*/
	public function __toString ()
	{
		try {
			return $this->get_name();
		} catch (Exception $e)
		{
			return $e->__toString();
		}
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_city @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param any $pParam
	* @return Zupal_Places_Cities
	*/
	public static function get_city ($pCity, $pState = NULL, $pCountry = NULL, $pMake_if_missing = TRUE)
	{
		$i = self::getInstance();
		$city = NULL;
		if ($pState instanceof Zupal_Place_IItem) $pState = $pState->identity();
		$pState = (int) $pState;
		if ($pCountry instanceof Zupal_Place_IItem) $pCountry = $pCountry->identity();
		
		if (is_numeric($pCity)):
			$city = $i->get($pCity);
			if (!$city):
			 	$city = new Zupal_Places_Cities(Zupal_Domain_Abstract::STUB);
			endif;
		elseif ($pCountry):
			if ($pCity instanceof Zupal_Places):
				return self::get_city($pCity->city, $pCity->state_id, $pCity->country_id);
			else:
				$select = $i->table()->select();
				$select->where('name LIKE ?', $pCity);
				if ($pState):
					$select->where('state = ?', $pState);
				endif;
				$row = $i->table()->fetchRow($select);
				if ($row):
					$city = new Zupal_Places_Cities($row);
				endif;
			endif;					
		endif;
		
		if (!$city): // return read only stub
			$city = new Zupal_Places_Cities($pMake_if_missing ? NULL : Zupal_Domain_Abstract::STUB);		
			$city->set_name($pCity);
			$city->state = $pState;
			$city->country = $pCountry;
			if ($pMake_if_missing){ $city->save();}
		endif;
		return $city;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ find_city @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param string $pName
	* @param string | Zupal_Places_States $pState
	* @param string | Zupal_Places_Countries $pCountry
	* @return <type>
	*/
	public static function find_city ($pName, $pState, $pCountry)
	{
		$table = self::getInstance()->table();

		if ((!$pCountry instanceof Zupal_Places_Countries) && $pCountry):
			$pCountry = Zupal_Places_Countries::get_country($pCountry);
		endif;

		if (!$pCountry):
			return NULL;
		endif;

		if ($pCountry->has_states):
			if ((!$pState instanceof Zupal_Places_States) && $pState):
				$pState = Zupal_Places_States::get_state($pState);
			endif;

			if (!$pState):
				return NULL;
			endif;

			$select = $table->select()
				->where('name LIKE ?', $pName)
				->where('state = ?', $pState->identity())
				->where('country LIKE ?', $pCountry->identity());
		else:
			$select = $table->select()
				->where('name LIKE ?', $pName)
				->where('country LIKE ?', $pCountry->identity());
		endif;

		$row = $table->fetchRow($select);
		if ($row):
			return new Zupal_Places_Cities($row);
		else:
			return NULL;
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_Instance = NULL;
	/**
	 *
	 * @param boolean $pReload
	 * @return Zupal_Places_Cities
	 */
	public static function getInstance($pReload = FALSE)
	{
		if ($pReload || is_null(self::$_Instance)):
		// process
			self::$_Instance = new Zupal_Places_Cities(Zupal_Domain_Abstract::STUB);
		endif;
		return self::$_Instance;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ state  @@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * @return Zupal_Places_States
	 */

	public function get_state ()
	{
		if ($this->state):
			return Zupal_Places_States::getInstance()->find($this->state);
		else:
			return NULL;
		endif;

		}

	public function set_state ($pValue) {
		$state = Zupal_Places_States::getInstance()->get_state($pValue);
		if ($state):
			$this->state = $state->identity();
		else:
			$this->state = 0;
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ country  @@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * @return Zupal_Places_Countries
	 */

	public function get_country (){
		if ($this->country):
			return Zupal_Places_Countries::getInstance()->find($this->country);
		else:
			return NULL;
		endif;
	}

	public function set_country ($pValue) {
		$country = Zupal_Places_Countries::getInstance()->get_country($pValue);
		if ($country):
			$this->country = $country->identity();
		else:
			$this->country = 0;
		endif;
	}

}