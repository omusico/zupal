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
		return new Zupal_Places_States($pID);
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
			self::$_Instance = new Zupal_Places_States(Zupal_Domain_Abstract::STUB);
		endif;
		return self::$_Instance;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_city @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param any $pParam
	* @return Zupal_Places_States
	*/
	public static function get_state ($pState, $country_id = NULL, $pCreate_if_missing = TRUE)
	{
		if ($pState instanceof Zupal_Places) return self::get_state($pState->state, $pState->country_id);
		if (is_numeric($pState)) return new Zupal_Places_States($pState);

		$i = self::getInstance();

		$select = $i->table()->select();
		if ($country_id) $select->where('country = ?', $country_id);
		$select->where('name LIKE ?', $pState);
		$select->orWhere('code LIKE ?', $pState);
		$row = $i->table()->fetchRow($select);
		if ($row) return new Zupal_Places_States($row);
		//@TODO: cache;
		// @TODO -- don't do for known contry states (like US)
		if($pCreate_if_missing):
			$state = new Zupal_Places_States();
			$state->set_name($pState);
			$state->set_country($country_id);
			$state->save();
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