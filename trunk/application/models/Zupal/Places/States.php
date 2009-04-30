<?php

class Zupal_Places_States
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_Instance = NULL;
	/**
	 *
	 * @param boolean $pReload
	 * @return Zupal_Places_States
	 */
	public static function getInstance($pReload = FALSE)
	{
		if ($pReload || is_null(self::$_Instance)):
		// process
			self::$_Instance = new Zupal_Place_Cities(Zupal_Donain_Abstract::STUB);
		endif;
		return self::$_Instance;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_city @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param any $pParam
	* @return Zupal_Places_States
	*/
	public static function get_state ($pParams)
	{
		$state = NULL;
		if ($pParams instanceof Zupal_Places):
			if ($pParams->state_id):
				$state = self::getInstance()->find($pParams->state_id);
				if ($state && $state->get_value() && ($state->get_value() != $pParams->state)):
					$pParams->state_id = 0;
					$table = self::getInstance()->table();
					$select = $table->select()
						->where('country = ?', $pParams->country_id)
						->where('name LIKE ?', $pParams->state)
						->orWhere('code LIKE ?', $pParams->state);
					$row = $table->fetchRow($select);
					if ($row):
						$state = new Zupal_Places_States($row);
					else:
						$state = new Zupal_Places_States();
						$state->set_value($pParams->state);
						$state->set_country($pParams->get_country());
						$state->save();
					endif;
				endif;
			elseif ($pParams->state):
				$state = self::get_state($pParams->state, $pParams->getCountry());
				if (!$state):
					$state = new Zupal_Places_Cities();
					$state->set_name($pParams->state);
					$state->set_country($pParams->get_country());
					$state->save();
				endif;
			endif; // else keep as null
		elseif (is_numeric($pParams)):
			$state = $this->getInstance()->find($pParams);
		else:
			$state = new Zupal_Places_States(Zupal_Domain_Abstract::STUB);
			$state->set_name($pParams); // return a neutrered state object.
		endif;
		return $state;
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