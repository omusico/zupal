<?php

/**
 * Description of Abstract
 *
 * @author daveedelhart
 */
abstract class Zupal_Domain_Abstract
implements Zupal_Domain_IDomain
{

	const STUB = '_asStub_';
	protected $_row = NULL;

	/**
	 * Creates an object based on the passed ID.
	 * NOTE: there is NO redundancy protection on domain objects because
	 * it is presumed that the underlying row has redundancy insulation.
	 *
	 * @param unknown_type $pID
	 */
	public function __construct ($pID = NULL)
	{
		if (is_object($pID)):
			$this->_row = $pID;
		elseif (!strcasecmp($pID, self::STUB)):
			$this->asStub();
		elseif ($pID):
			$this->load($pID);
		else:
			$this->newRow();
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ new @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	protected function newRow($pData = NULL)
	{		
		$this->_row = $this->table()->fetchNew();
		$row = $this->_row;
		if ($pData):
			$fields = array_keys($row->toArray());
			if ($pData instanceof stdClass):
				foreach($fields as $field):
					if (property_exists($pData, $field)):
						$row->$field = $pData->field;
					endif;
				endforeach;
			elseif ($pData instanceof Zend_Db_Table_Row_Abstract):
				foreach($fields as $field):
					$row->$field = $pData->field;
				endforeach;
			endif;
		endif;
		
		return $row;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 * @return Zupal_Table_Abstract
 */
	public function table ()
	{
		$tc = $this->tableClass();
		if (! array_key_exists($tc, self::$_tables))
		{
			self::$_tables[$tc] = new $tc();
		}

		return self::$_tables[$tc];
	}

	private static $_tables = array();

	protected abstract function tableClass ();

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ identity @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function identity()
	{
		if ($this->isStub()) return NULL;
		//@NOTE: elaborate retrieval for debugging purposes -- should be simplified for production. 
		$id_field = $this->table()->idField();
		if ($this->_row instanceof stdClass ):
			if (property_exists($this->_row, $id_field)):
				return $this->_row->$id_field;
			else:
				throw new Exception (__METHOD__ . ': bad id ' . $id_field . ' requested from ' . get_class($this));
			endif;
		elseif (is_object($this->_row)):
			return $this->_row->$id_field;
		else:
			throw new Exception(__METHOD__ . ': non object row in ' . $this->tableClass() . ': ' . print_r($this->_row, 1));
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/*
 * Loads the row with data from the database -- or mock source.
 */

	protected function load ($pID)
	{
		if (is_object($pID)):
			if ($pID instanceof Zend_Db_Table_Row):
				$this->_row = $pID;
				return;
			else:
				throw new Exception(__METHOD__ . ': Non integer ' . print_r($pID, 1) . ' passed to ' . __CLASS__);
			endif;
		endif;
		$pID = (int) $pID;

	//	echo '<p>loading ' . $pID . ' from ' . $this->tableClass() . '</p>';
		if ($pID):
			$hits = $this->table()->find($pID);
			if ($hits): 
				$this->_row = $hits->current();
			else:
				error_log('cannot find ' . $pID . ' in ' . $this->tableClass());
			endif;
		endif;
		if (!$this->_row) $this->_row = $this->table()->createRow();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Status tests @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 * isSaved and loaded answer two similar questions - but the difference is important.
 * isSaved uses the database as the system of record. If the loaded ID is in the database (or is zero)
 * the isSaved() method returns TRUE.
 *
 * loaded uses the object's ID field as the system of record; if the object has a row and that row's id field is nonzero,
 * the loaded() method returns TRUE;
 */
	//@TODO: test
	public function isSaved()
	{
		$id_field = $this->table()->idField();
		$table_name = $this->table()->getName();
		$id = intval($this->$id_field);

		if (!$id):

			return FALSE;

		else:

			$sql = "SELECT count(`$id_field`) FROM `$table_name` WHERE `$id_field` = $id";
			$tally = $this->table()->getAdapter()->fetchOne($sql);

			return $tally; // note -- any (unlikely) duplication of an ID key in a table has to be handled downstream of this method

		endif;
	}

	public function loaded()
	{
		if (!$this->_row) return FALSE;
		if (is_object($this->_row)):
			$id_field = $this->table()->idField();
			$id = @$this->_row->$id_field;
			return $id > 0 ? TRUE : FALSE;
		else:
			return FALSE;
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ find @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * return one (or more) row; should at least be able to find a single
	 * domain object; more complex queries should be managed by the domain
	 * object as well.
	 *
	 * The idea behind the find function is to localize all selection SQL to the domain object.
	 * While the generic domain function will work for most cases (finding by one or more parameter,
	 * simple comparison) some more abstruse searches (such as multi table domains)
	 * might require a more sophisticated find mechanism.
	 *
	 * @param unknown_type $pParams -- either an id, or a hashet of parameters.
	public abstract function find ($pParams, $pSort = NULL);
	 */
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ find @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * Your basic query gateway.
	 * Find returns an array or rowset of
	 * all the matching results (even if there is one or no results.)
	 * findOne on the other hand returns only one (the first) match.
	 *
	 * NOTE: cannot handle joins -- use find_from_sql with table = false for join based results.
	 */
	
	public function find(array $pParams = NULL, $pSort = NULL)
	{
		$rows = array();
		if (is_numeric($pParams)):
			$rows = $this->get($pParams);
		 else:
			$select = $this->_select($pParams, $pSort);

			$table_rows = $this->table()->fetchAll($select);
			foreach($table_rows as $row):
				$rows[] = $this->get($row);
			endforeach;
		endif;
		return $rows;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ findAll @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function findAll ($pSort = NULL)
	{
		return $this->find(NULL, $pSort);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ findOne @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * @return Zupal_Domain_Abstract
	 */
	public function findOne(array $pParams = NULL, $pSort = NULL)
	{
		$select = $this->_select($pParams, $pSort);
		$row = $this->table()->fetchRow($select);
		return $row ? $this->get($row) : NULL;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _select @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pParams
	* @return Zend_Db_Table_Select
	*/
	protected function _select ($pParams, $pSort = NULL)
	{		
		$select = $this->table()->select();
		if (is_array($pParams) && count($pParams)):
			foreach($pParams as $field => $value):
				if (is_array($value)):
					list($value, $operator) = $value;
				else:
					$operator = '=';
				endif;
				$select->where("$field $operator ?", $value);
			endforeach;
		endif;
		if ($pSort):
			$select->order($pSort);
		endif;
		return $select;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * returns a domain object for the passed ID.
	 * NOTE: does not check for the EXISTENCE of a row with said ID.
	 * Also accepts rowset objects (for pre-population) -- see __construct.
	 *
	 * Note that while this method is not particularly useful (has no advantages over $n = new Class($id),
	 * it is used internally.
	 *
	 * @return Zupal_Domain_IDomain
	 */
	public abstract function get ($pID);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __get @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * This is a "Magic Method" passthrough to the row object/array
	 */
	public function __get ($pField)
	{
		if (is_object($this->_row)):
		 	if ($this->_row instanceof stdClass):
		 		if (property_exists($this->_row, $pField)):
		 			return $this->_row->$pField;
		 		else:
		 			throw new Exception(__METHOD__ . ": mock field $pField missing from " . get_class($this) . '(' . print_r($this->_row, 1) . ')');
		 		endif;
			elseif($this->_row instanceof Zend_Db_Table_Row_Abstract):
				return $this->_row->__get($pField);
			else:
				throw new Exception(__METHOD__ . ': bad row object (' . get_class($this->_row) . ') polled for field ' . $pField);
		 	endif;
		elseif (is_array($this->_row)):
			if (! array_key_exists($pField, $this->_row)):
				throw new Exception(__METHOD__ . ': no ' . $pField . ' in keys (' . join(',', array_keys($this->_row)) . ')');

				endif;
			return $this->_row[$pField];
		else:
			throw new Exception(__METHOD__ . ': bad row polled for field ' . $pField);
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function __set($pField, $pValue)
	{
		//@NOTE: __get accepts array data but this method presumes row object. One or the other direction needs to be solid. 
		$this->_row->$pField = $pValue;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function save()
	{
		//@NOTE: __get accepts array data but this method presumes row object. One or the other direction needs to be solid.
		if ($this->_asStub):
			throw new Exception('Attempt to save a stub of ' . get_class($this));
		elseif ($this->_row):
			$this->_row->save();
		else:
			throw new Exception(__METHOD__ . ': Cannot save empty row');
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ asStub @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	protected $_asStub = FALSE;
	public function asStub()
	{
		$this->_asStub = TRUE;
	}
	public function isStub() { return $this->_asStub; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ delete @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function delete()
	{
		if (!strcasecmp(Zupal_Config::get_env(), 'test')):
			$this->_row = $this->table()->delete_mock_row($this->_row);
		else:
			$this->_row->delete();
		endif;

	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ toArray @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function toArray ()
	{
		return $this->_row->_toArray();
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ find_from_sql @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * This method takes on faith that the SQL has been constructed
	 * to return an array of (at least) identities,
	 * and that they have not been mismapped or renamed.
	 *
	 * @TODO: validate this!
	 * @return Zupal_Domain_Abstract[];
	 */

	public function find_from_sql($pSQL, $pTable = TRUE, $pBy_ID = TRUE)
	{
		if ($pTable):
			$base = $this->table();
		else:
			$base = $this->table->getAdapter();
		endif;

		if (is_array($pSql)):
			$rowset = call_user_func_array(array($base, 'fetchAll'), $pSQL);
		else:
			$rowset = $base->fetchAll($pSQL);
		endif;

		$rows = array();

		if ($pBy_ID):
			$id_field = $this->table()->idField();
			foreach ($rowset as $data):
				$rows[] = $this->get($data[$id_field]);
			endforeach;
		else:
			foreach ($rowset as $data):
				$rows[] = $this->get($data);
			endforeach;
		endif;

		return $rows;
	}

	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ link @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	const LINK_TEMPLATE = '<a href="%s" %s>%s</a>';

	/**
	 * returns a markup link to this item
	 *
	 * @param String $pURL
	 * @param String $pClass
	 * @return String
	 */
	public function link ($pURL, $pClass = NULL)
	{
		$class = $pClass ? sprintf(' class="%s" ', $pClass) : '';

		return sprintf(self::LINK_TEMPLATE, $pURL, $class, $this);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __call @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pName
	* @return <type>
	*/
	public function __call ($pName, $pParams)
	{
		$name = explode('_', $pName);
		if (count($name) > 1):
			foreach($name as $n => $v) if ($n) $name[$n] = ucfirst($v);
			$alt = join('', $name);
		else:
			$alt = strtolower(preg_replace('/(?<=[a-z])(?=[A-Z])/','_',$pName));
		endif;
		if (method_exists($this, $alt)):
			return call_user_func_array(array($this, $alt), $pParams);
		endif;

		throw new Exception("No function $pName or $alt in " . get_class($this));
	}

}

