<?php

/**
 * Domain objects overlay the database objects and add business functionality 
 * over the row object. Note that as a rule they should have little to no 
 * local variables -- the row object serves as the primary repository for data.
 *
 */
abstract class Zupal_Domain
{

	const STUB = '_AS_STUB_';
	
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
			$this->as_stub();
		elseif ($pID):
			$this->load($pID);
		else:
			$this->new_row();
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ new @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	protected function new_row()
	{
		if (! strcasecmp(Zupal_Config::get_env(), 'test')):
			$this->_row = new stdClass();
		else:
			$this->_row = $this->get_table()->fetchNew();
		endif;		
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 * @return Zupal_Table_Abstract
 */
	public function get_table ()
	{
		if (! array_key_exists($this->get_table_class(), self::$_tables))
		{
			$tc = $this->get_table_class();
			self::$_tables[$this->get_table_class()] = new $tc();
		}
		
		return self::$_tables[$this->get_table_class()];
	}
	
	private static $_tables = array();

	protected abstract function get_table_class ();
	
	public abstract function __toString();
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ identity @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public function identity()
	{
		$id_field = $this->get_table()->id_field();
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
		if (Zupal_Config::testing()):
			if ($pID > 0):
				$this->_row = $this->get_table()->get_mock_row($pID, TRUE);
			 else:
				$this->_row = new stdClass();
			endif;
		 else:
			if ($pID > 0):
				$this->_row = $this->get_table()->get_row($pID);
			 else:
				$this->_row = $this->get_table()->createRow();
			endif;
		endif;
	
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Status tests @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
/**
 * is_saved and loaded answer two similar questions - but the difference is important.
 * is_saved uses the database as the system of record. If the loaded ID is in the database (or is zero)
 * the is_saved() method returns TRUE.
 * 
 * loaded uses the object's ID field as the system of record; if the object has a row and that row's id field is nonzero,
 * the loaded() method returns TRUE; 
 */
	//@TODO: test
	public function is_saved()
	{
		$id_field = $this->get_table()->id_field();
		$table_name = $this->get_table()->get_name();
		$id = intval($this->$id_field);
		
		if (!$id):
		
			return FALSE;
			
		elseif(Zupal_Config::testing()):
		
			if($this->get_table()->get_mock_row($id)):
				return TRUE;
			else:
				return FALSE;
			endif;
			
		else:
		
			$sql = "SELECT count(`$id_field`) FROM `$table_name` WHERE `$id_field` = $id";			
			$tally = $this->get_table()->getAdapter()->fetchOne($sql);
			
			return $tally; // note -- any (unlikely) duplication of an ID key in a table has to be handled downstream of this method
			
		endif;
	}
	
	public function loaded()
	{
		if (!$this->_row) return FALSE;
		if (is_object($this->_row)):
			$id_field = $this->get_table()->id_field();
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
	 * Find_one on the other hand returns only one (the first) match. 
	 */
	public function find($pParams, $pSort = NULL)
	{
		$rows = array();
		if (is_numeric($pParams)):
			$rows = $this->get($pParams);
		 elseif (Zupal_Config::testing()):
			$rows = $this->mock_find_filtered($pParams, $pSort);
		 else:
			$select = $this->get_table()->select();
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
			
			$table_rows = $this->get_table()->fetchAll($select);
			//error_log(__METHOD__ . ': finding ' . $select->assemble());
			foreach($table_rows as $row):
				$rows[] = $this->get($row);
			endforeach;
		endif;
		return $rows;
	}
		
	/**
	 * This is a simple handler for test data that eliminates all values 
	 * that are not equal to the key->value associations in the parameter.
	 */
	protected function mock_find_filtered(array $pParams, $pSort = NULL)
	{
		$records = $this->find_all($pSort);
				
		$out = array();
		foreach($records as $record):
			foreach($pParams as $key => $value):
				if ($record->$key != $value) continue 2;
			endforeach;
			$out[] = $record;
		endforeach;
		return $out; 
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ find_all @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public function find_all ($pSort = NULL)
	{
		if (Zupal_Config::testing()):
			$out = array();
			
			$id_array = $this->get_table()->get_all_rows($pSort, TRUE);
			foreach ($id_array as $id)
			{
				$out[] = $this->get($id);
			}
			return $out;
		 else:
			return $this->find(NULL, $pSort);
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ find_one @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	/**
	 * @return Zupal_Domain
	 */
	public function find_one($pParams, $pSort = NULL)
	{
		if (Zupal_Config::testing()):
			return array_pop($this->mock_find_filtered($pParams, $pSort));
		else:
			$select = $this->get_table()->select();
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
			$row = $this->get_table()->fetchRow($select, $pSort);
			return $row ? $this->get($row) : NULL;
		endif;
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ report_all @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	/**
	 * Gets an aggregate report of all the rows in this table. 
	 * Spendy proposition for a large rowset! use with care. 
	 * remember: report methods tend to recurse!
	 */
	
	public function report_all($pSort = NULL)
	{
		$rows = $this->find_all($pSort);
		$out = array();
		foreach($rows as $row):
			$out[] = $row->report();
		endforeach;
		return $out;
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	/**
	 * returns a domain object for the passed ID. 
	 * NOTE: does not check for the EXISTENCE of a row with said ID. 
	 * Also accepts rowset objects (for pre-population) -- see __construct. 
	 * @return Zupal_Domain
	 */
	public abstract function get ($pID);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __get @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	/**
	 * This is a "Magic Method" passthrough to the row object. 
	 */
	public function __get ($pField)
	{
		if (is_array($this->_row)):
			if (! array_key_exists($pField, $this->_row)):
				throw new Exception(__METHOD__ . ': no ' . $pField . ' in keys (' . join(',', array_keys($this->_row)) . ')');
			
				endif;
			return $this->_row[$pField];
		 elseif (is_object($this->_row)):
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
		if ($this->_as_stub):
			throw new Exception('Attempt to save a stub of ' . get_class($this));
		elseif ($this->_row):
			if (!strcasecmp(Zupal_Config::get_env(), 'test')):
				if (property_exists($this->_row, Zupal_Table_Abstract::DELETED_FIELD)):
					throw new Exception(__METHOD__ . ': attempt to save a deleted row ' . print_r($this->_row, 1));
				endif;
				$this->get_table()->add_mock_row($this->_row);
			else:
				$this->_row->save();
			endif;
		else:
			throw new Exception(__METHOD__ . ': Cannot save empty row');
		endif;
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ as_stub @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	protected $_as_stub = FALSE;
	public function as_stub()
	{
		$this->_as_stub = TRUE;
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ delete @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public function delete()
	{
		if (!strcasecmp(Zupal_Config::get_env(), 'test')):
			$this->_row = $this->get_table()->delete_mock_row($this->_row);
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
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ mock_join @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	/**
	 * This ultra hacky method reflects the joining of data onto a standard class object
	 * much as a SQL join does. It ias not been robustly tested. 
	 * In general it is better to write custom methods that request individual joined records
	 * by ID rather than using complex queries -- however there comes a time when an aggregate
	 * query is the only efficient way to pull a set of data, and this method exists to ensure
	 * that the aggregate query is mockable. 
	 */
	
	public function mock_join(
		array $pParams
	)
	{
		$out = new stdClass();
		
		if (array_key_exists('columns', $pParams)):
			foreach($pParams['columns'] as $field):
				$out->$field = $this->_row->$field;
			endforeach;
			unset($pParams['columns']);
		else:
			foreach($this->_row as $field => $value):
				$out->$field = $value;
			endforeach;
		endif;
		
		foreach($pParams['tables'] as $table):
			$name = $table['name'];
			
			$class = str_replace('_', ' ', $name);
			$class = ucwords($class);
			$class = str_replace(' ', '_', $class);
			$class = "Zupal_$class";
			
			if (array_key_exists('function', $table)):
				$function = $table['function'];
				$join_row = $this->$function();
				if ($join_row):
					$join_row = $join_row->data();
				endif;
			elseif (array_key_exists('foreign', $table)):
				$foreign = $table['foreign'];
				$foreign_key = $this->_row->$foreign;
				$join_row = new $class($foreign_key);
			elseif (array_key_exists('remote', $table)):
				$stub = new $class(Zupal_Domain::STUB);
				$id_field = $this->id_field();
				$id = $this->$id_field;
				$remote = $table['remote'];
				$join_row = array_pop($stub->find(
					array(
						$remote => $id
					)
				));
			else:
				$join_row = NULL;
			endif;
			
			if ($join_row):
				if (array_key_exists('column', $table)):
					$columns = $table['column'];
					
					foreach($columns as $column):
						if (is_array($column)):
							list($column, $out_field) = $column;
						else:
							$out_field = $column;
						endif;
						$out_field = property_exists($out, $out_field) ? "$name.$out_field" : $out_field;
						$out->$out_field = $join_row->$column;						
					endforeach;
				else:
					foreach($join_row as $column => $value):
						$out_field = property_exists($out, $column) ? "$name.$column" : $column;
						$out->$out_field = $value;
					endforeach;
				endif;
			else:
				if (array_key_exists('column', $table)):
					$columns = $table['column'];
					
					foreach($columns as $column):
						$out_field = property_exists($out, $column) ? "$name.$column" : $column;
						$out->$out_field = NULL;					
					endforeach;
				else:
					// @TODO: stub null rows
				endif;
			
			endif;
			
		endforeach;
		
		return $out;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ data @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	/**
	 * returns the data element of this object as a stdobj.
	 * Note -- even in test mode the returned value is
	 * independant of the Domain object that produced it --
	 * altering the result of data() will not have any effect 
	 * on the actual data; this method is intended for reading / reflection only.
	 *
	 * @return stdClass;
	 *
	 */
	
	public function data()
	{
		$out = new stdClass();
		if ($this->_row instanceof Zend_Db_Table_Row_Abstract):
			$source = $this->_row->toArray();
		else:
			$source = $this->_row;
		endif;
		
		foreach($source as $field => $value):
			$out->$field = $value;
		endforeach;
		return $out;
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ report @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	 * @return Zupals_Reports
	 */
	
	abstract public function report($pContext = NULL);
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ report @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	/**
	 * @var Zupal_Report
	 */
	private $_report = NULL;
	/**
	 * note -- this is a CACHED version of report (above); both methods 
	 * fill the same need -- get_report simply gives you the option of a cacheable version. 
	 *
	 * @return Zupal_Report
	 */
	function get_report($pContext = NULL, $pReload = FALSE)
	{
		if ($pReload || is_null($this->_report) || $this->_report->get_context() != $pContext):
			// process
			$this->_report = $this->report($pContext);
		endif;
		return $this->_report;
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
		$rowset = $this->get_table()->getAdapter()->fetchAll($pSQL);
		$rows = array();
		
		foreach ($rowset as $data):
			$id = $data[$this->get_table()->id_field()];
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
