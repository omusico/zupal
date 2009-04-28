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
		return $this->get_name();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_city @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param any $pParam
	* @return Zupal_Places_Cities
	*/
	public function get_city ($pParams)
	{
		$city = NULL;
		if ($pParams instanceof Zupal_Places):
			if ($pParams->city_id):
				$city = self::getInstance()->find($pParams->city_id);
			elseif ($pParams->city):
				$city = self::find_city($pParams->city, $pParams->state_id, $pParams->country_id);
				if (!$city):
					$city = new Zupal_Places_Cities();
					$city->set_name($pParams->city);
					$city->set_state($pParams->get_state());
					$city->set_country($pParams->get_country());
					$city->save();
				endif;
			endif; // else keep as null
		elseif (is_numeric($pParams)):
			$city = $this->getInstance()->find($pParams);
		else:
			$city = new Zupal_Places_Cities(Zupal_Domain_Abstract::STUB);
			$city->set_name($pParams); // return a neutrered city object.
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_Instance = NULL;
	public static function getInstance($pReload = FALSE)
	{
		if ($pReload || is_null(self::$_Instance)):
		// process
			self::$_Instance = new Zupal_Place_Cities(Zupal_Donain_Abstract::STUB);
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