<?php

class Zupal_Places_Countries
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
		return new Zupal_Places_Countries($pID);
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
	 * @return Zupal_Places_Countries;
	 */
	public static function getInstance($pReload = FALSE)
	{
		if ($pReload || is_null(self::$_Instance)):
		// process
		self::$_Instance = new Zupal_Places_Countries(Zupal_Domain_Abstract::STUB);
		endif;
		return self::$_Instance;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ as_list @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return array
	*/
	private static $_as_list = NULL;
	public static function as_list ()
	{
		if (is_null(self::$_as_list)):
			$table = self::getInstance()->table();
			$sql = sprintf('SELECT %s, name FROM %s order by name', $table->idFIeld(), $table->tableName() );

			$list = $table->getAdapter()-> fetchPairs($sql);
			self::$_as_list = $list;
		endif;
		return self::$_as_list;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_country @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param variant $pParam
	* @return Zupal_Places_Countries
	*/
	private static $_countries = array();

	public static function get_country ($pParam)
	{
		if (is_string($pParam)):
			$pParam = trim(strtolower($pParam));
			if (!array_key_exists($pParam, self::$_countries)):
				$table = self::getInstance()->table();
				$select = $table->select()->where('code LIKE ?', $pParam)
					->orWhere('name LIKE ?', $pParam);
				$country = $table->fetchRow($select);
				if ($country):
					self::$_countries[$pParam] = new Zupal_Places_Countries($country);
				endif;
			endif;

			if(array_key_exists($pParam,  self::$_countries)):
				return self::$_countries[$pParam];
			else:
				return NULL;
			endif;
		endif;
		
		return NULL;
	}
}