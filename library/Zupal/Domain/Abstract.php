<?php

/**
 * Description of Abstract
 *
 * @author daveedelhart
 */
class Zupal_Domain_Abstract
implements Zupal_IDomain
{

	const STUB = '_asStub_';

	/**
	 * Creates an object based on the passed ID.
	 * NOTE: there is NO redundancy protection on domain objects because
	 * it is presumed that the underlying row has redundancy insulation.
	 *
	 * @param unknown_type $pID
	 */
	public function __construct ($pID = NULL)
	{
		if ($pID instanceof Zend_Db_Table_Row):
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

	protected function newRow()
	{
		if (! strcasecmp(Zupal_Config::get_env(), 'test')):
			$this->_row = new stdClass();
		else:
			$this->_row = $this->table()->fetchNew();
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 * @return Zupal_Table_Abstract
 */
	public function table ()
	{
		if (! array_key_exists($this->tableClass(), self::$_tables))
		{
			$tc = $this->tableClass();
			self::$_tables[$this->tableClass()] = new $tc();
		}

		return self::$_tables[$this->tableClass()];
	}

	private static $_tables = array();

	protected abstract function tableClass ();

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ identity @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function identity()
	{
		$id_field = $this->table()->idField();
		return $this->_row->$id_field;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/*
 * Loads the row with data from the database -- or mock source.
 */
	protected $_row = NULL;

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

		if ($pID > 0):
		$this->_row = $this->table()->getRow($pID);
		 else:
			$this->_row = $this->table()->createRow();
		endif;
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
	 */
	public function find($pParams, $pSort = NULL)
	{
		$rows = array();
		if (is_numeric($pParams)):
			$rows = $this->get($pParams);
		 elseif (Zupal_Config::testing()):
			$rows = $this->mock_find_filtered($pParams, $pSort);
		 else:
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
			$select->order($pSort);

			$table_rows = $this->table()->fetchAll($select);
			//error_log(__METHOD__ . ': finding ' . $select->assemble());
			foreach($table_rows as $row):
				$rows[] = $this->get($row);
			endforeach;
		endif;
		return $rows;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ findAll @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function findAll ($pSort = NULL)
	{
		if (Zupal_Config::testing()):
			$out = array();

			$id_array = $this->table()->get_all_rows($pSort, TRUE);
			foreach ($id_array as $id)
			{
				$out[] = $this->get($id);
			}
			return $out;
		 else:
			return $this->find(NULL, $pSort);
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ findOne @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * @return Zupal_Domain
	 */
	public function findOne($pParams, $pSort = NULL)
	{
		if (Zupal_Config::testing()):
			return array_pop($this->mock_find_filtered($pParams, $pSort));
		else:
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
			$row = $this->table()->fetchRow($select, $pSort);
			return $row ? $this->get($row) : NULL;
		endif;
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
	 * @return Zupal_Domain
	 */
	public abstract function get ($pID);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __get @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * This is a "Magic Method" passthrough to the row object.
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
		$this->_row->$pField = $pValue;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function save()
	{
		if ($this->_asStub):
			throw new Exception('Attempt to save a stub of ' . get_class($this));
		elseif ($this->_row):
			if (!strcasecmp(Zupal_Config::get_env(), 'test')):
				if (property_exists($this->_row, Zupal_Table_Abstract::DELETED_FIELD)):
					throw new Exception(__METHOD__ . ': attempt to save a deleted row ' . print_r($this->_row, 1));
				endif;
				$this->table()->add_mock_row($this->_row);
			else:
				$this->_row->save();
			endif;
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ delete @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function delete()
	{
		if (!strcasecmp(Zupal_Config::get_env(), 'test')):
			$this->_row = $this->table()->delete_mock_row($this->_row);
		else:
			$this->_row->delete();
		endif;

	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ toArray @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function toArray()
	{
		$out = array();

		foreach($this->_row as $field => $value):
			$out[$field] = $value;
		endforeach;

		return $out;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ find_from_sql @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * This method takes on faith that the SQL has been constructed
	 * to return an array of (at least) identities, and that they have not been mismapped or renamed.
	 *
	 * @TODO: validate this!
	 * @return Zupal_Domain[];
	 */

	public function find_from_sql($pSQL)
	{
		$rowset = $this->table()->getAdapter()->fetchAll($pSQL);
		$rows = array();
		$id = $data[$this->table()->idField()];

		foreach ($rowset as $data):
			$rows[] = $this->get($id);
		endforeach;

		return $rows;
	}

	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ link @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	const LINK_TEMPLATE = '<a href="%s">%s</a>';

	/**
	 * returns a markup link to this item
	 *
	 * @param String $pURL
	 * @return String
	 */
	public function link ($pURL)
	{
		return sprintf(self::LINK_TEMPLATE, $pURL, $this);
	}

}

