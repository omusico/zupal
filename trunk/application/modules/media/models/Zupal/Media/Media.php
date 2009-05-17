<?

class Zupal_Media_Media
extends Zupal_Domain_Abstract 
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ virtual fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 * by overriding the magic get/set, we treate the person fields as if they belonged
 * to this domain. 
 */

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * @see CPF_Formset_Domain::get_table_class()
	 * -- note -- this is "boilerplate" code that can be put into any new domain
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
	 *
	 */
	public function get ($pID)
	{
		return new self($pID);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * note -- this "boilderplate" can be dropped anywhere.
 */
	private static $_Instance = NULL;

/**
 *
 * @return Zupal_People
 */
	public static function getInstance()
	{
		if (is_null(self::$_Instance)):
		// process
		self::$_Instance = new self();
		endif;
		return self::$_Instance;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
		
	private static $_fields = NULL;
	public static function fields()
	{
		if (is_null(self::$_fields)):
			// process
			self::$_fields = array_keys(self::getInstance()->toArray());
		endif;
		return self::$_fields;
	}
}