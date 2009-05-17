<?

class Zupal_Media_Artists
extends Zupal_Node_Abstract
{

	protected $_joins = array(
		'person' => array(
			'local_key' => 'person_id',
			'value' => NULL,
			'class' => 'Zupal_People'
		),
		'media' => array(
			'local_key' => 'media_id',
			'value' => NULL,
			'class' => 'Zupal_Media_Media'
		)
	);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ field_map @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_field_map = NULL;
	public static function field_map($pReload = FALSE)
	{
		if (is_null(self::$_field_map)):
		// process
			self::$_field_map = array();
			foreach(Zupal_People::fields() as $field):
				self::$_field_map['person_' . $field] = array('join' => 'person', 'field' => $field);
			endforeach;

			foreach(Zupal_Media_Media::feilds() as $field):
				self::$_field_map['media_' . $field] = array('join' => 'media', 'field' => $field);
			endforeach;
		endif;
		return self::$_field_map;
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ virtual fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 * by overriding the magic get/set, we treate the person fields as if they belonged
 * to this domain. 
 */
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __get @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pField
	* @return scalar
	*/
	public function __get ($pField)
	{
		$map =  self::field_map();
		if (array_key_exists($pField, $map)):
			extract($map[$pField]);
			return $this->get_join($join)->$field;
		endif;
		
		return parent::__get($pField);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __set @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pField, $pValue
	* @return scalar
	*/
	public function __set ($pField, $pValue)
	{
		$s = strlen('person_');
		$pre = substr($pField, 0, $s);

		if ($pre == 'person_'):
			$sub = substr($pField, $s);

			if(in_array($sub, Zupal_People::fields())):
				return $this->person()->$sub = $pValue;
			endif;
		endif;

		return parent::__set($pField, $pValue);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return string
	*/
	public function name ()
	{
		if ($this->performs_as):
			return $this->performs_as;
		else:
			return $this->person()->name();
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ person @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return Zupal_People
	*/
	public function person ($pCreate_if_empty = TRUE)
	{
		return $this->get_joined('person', $pCreate_if_empty);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_person @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param int | Zupal_People $pParam
	* @return
	*/
	public function set_person ($pParam)
	{
		if (is_numeric($pParam)):
			$this->person_id = $pParam;
			if (array_key_exists('people', $this->_joins)):
				unset($this->_joins['people']);
			endif;
		elseif ($pParam instanceof Zupal_People):
			$this->_joins['people'] = $pParam;
			$this->person_id = $pParam->identity();
		endif;
	}

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

}