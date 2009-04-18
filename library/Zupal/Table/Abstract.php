<?php

/**
 * Zupal_Table_Abstract
 *  
 * @author daveedelhart
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

abstract class Zupal_Table_Abstract extends Zend_Db_Table_Abstract
{
	protected $_id_field = 'id'; // note this standard convention is NOT automatically overrriden by table definition. 
	protected $_name = '___OVERRIDE ME ____';
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public function get_name()
	{
		return $this->_name;
	}	

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __construct @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public function __construct($config = NULL)
	{
		if (!Zupal_Config::testing()):
			parent::__construct($config);
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ id_field @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public function id_field()
	{
		return $this->_id_field;
	}

	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ mock_table @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	private $_mock_table = NULL;

	/**
	 * an ini clump of mock data
	 *
	 * @return Zend_Config_Ini
	 */
	function get_mock_table ()
	{
		if (is_null($this->_mock_table)):
			$value = self::mock_table($this->_name);
			// process
			$this->_mock_table = $value;
		
		endif;
		return $this->_mock_table;
	}

	public static function mock_table($pName)
	{
		return new Zend_Config_Ini(realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '../mock_data.ini',
		 strtolower($pName));		
	}
	
	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_mock_row @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	function get_mock_row ($pID)
	{
		$table_data = $this->get_mock_table();
		if (! $table_data):
			throw new Exception(__METHOD__ . "Cannot find table data for mock row $pID in {$this->_name}.");
		endif;
		if ($table_data->$pID):
			$row = $table_data->$pID;
			
			$row_std = new stdClass();
			foreach($row as $field => $value):
				$row_std->$field = $value;
			endforeach;
		else:
			$mock = $this->get_mock_new_rows();
			if ($mock->$pID):
				$row_std = $mock->$pID;
			else:
				throw new Exception(__METHOD__ . ': cannot find row ' . $pID . ' in ' . get_class($this));
			endif;
		endif;
		return $row_std;
	}

	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_row @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public function get_row ($pID)
	{
		$rows = $this->find($pID);
		if (! $rows->current()):
			throw new Exception(__CLASS__ . ": cannot find id $pID; " . __METHOD__);
		
		endif;
		
		return $rows->current();
	}

	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_all_rows @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public function get_all_rows ($pSort = NULL, $pID_only = FALSE)
	{
		if (! strcasecmp(Zupal_Config::get_env(), 'test'))
		{
			$result = self::get_mock_table();
			$rows = array();
			$id_field = $this->id_field();
			
			foreach($result as $row):
				$row_std = new stdClass();
				foreach($row as $field => $value):
					$row_std->$field = $value;
				endforeach;
				$rows[$row->$id_field] = $row_std;
			endforeach;
			$new_rows = $this->get_mock_new_rows();
			foreach($new_rows as $new_row)
			{
				if (!is_object($new_row)):
					continue;
				elseif(property_exists($new_row, self::DELETED_FIELD)):
					if (array_key_exists($new_row->$id_field, $rows)):
						unset($rows[$new_row->$id_field]);
					endif;
				else:
					$rows[$new_row->$id_field] = $new_row;
				endif;
			}
						
			if ($pSort):
				$sort_table = "stdobj_sort_$pSort";
				$result = usort($rows, $sort_table);
				if (!$result):
					echo '<p>', __METHOD__, 'object: ', get_class($this), '; Bad usort for &quot;', $sort_table,'&quot;</p>';
				endif;
			else:
				ksort($rows);
			endif;
			
			if ($pID_only):
				$id_rows = array();
				foreach($rows as $row):
					$id_rows[] = (int) $row->$id_field;
				endforeach;
				$rows = $id_rows;
			endif;
			
			
		} else {
			if ($pID_only)
			{
				$order = $pSort ? $pSort : $this->_id_field;
				$sql = sprintf("SELECT `%s` FROM %s ORDER BY %s", $this->_id_field, $this->_name, $order);
				$rows = $this->getAdapter()->fetchCol($sql);
			} else {
				$rows = $this->fetchAll(NULL, $pSort);
			}
		}
		return array_values($rows);
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_mock_session @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	/**
	 * @return Zend_Session_Namespace
	 */
	public function get_mock_session()
	{
		return new Zend_Session_Namespace($this->_name . '_mock_new_rows');
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ last_mock_insert_id @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	protected function last_mock_insert_id()
	{
		$ids = $this->get_all_rows(NULL, TRUE);
		return max($ids);
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ add_mock_row @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public function add_mock_row(stdClass $pRow)
	{
		$id_field = $this->id_field();
		if (!property_exists($pRow, $id_field) || (!$pRow->$id_field)):
			$id = $this->last_mock_insert_id() + 1;
			$pRow->$id_field = $id;
		else:
			$id = $pRow->$id_field;
		endif;
		
		$this->get_mock_session()->$id = $pRow;
		return $pRow;
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ clear_mock_rows @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public function clear_mock_rows()
	{
		$this->get_mock_session()->unsetAll();
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ clear_all_mock_rows @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public static function clear_all_mock_rows()
	{
		$stubs = array();
		
		$stubs[] = new Zupal_Table_Formsets(Zupal_Domain::STUB);
		$stubs[] = new Zupal_Table_Forms(Zupal_Domain::STUB);
		$stubs[] = new Zupal_Table_User_Formsetjobs(Zupal_Domain::STUB);
		$stubs[] = new Zupal_Table_User_Jobs(Zupal_Domain::STUB);
		$stubs[] = new Zupal_Table_Users(Zupal_Domain::STUB);
		
		foreach($stubs as $stub) $stub->clear_mock_rows();
		
	}
	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ mock_new_rows @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
		
	private $_mock_new_rows = NULL;
	function get_mock_new_rows()
	{
		if (is_null($this->_mock_new_rows)):		
			$sn = $this->get_mock_session();
			if ($sn == NULL) $sn = FALSE;
			// process
			$this->_mock_new_rows = $sn;
		endif;
		return $this->_mock_new_rows;
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ delete_mock_row @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	const DELETED_FIELD = '__deleted__';
	
	public function delete_mock_row(stdClass $pRow)
	{
		$id_field = $this->id_field();
		$deleted_field = self::DELETED_FIELD;
		
		if ($pRow->$id_field):
			$pRow->$deleted_field = TRUE;
			$this->add_mock_row($pRow);
		endif;
		
		return $pRow;
	}
}
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ sort functions @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 * note -- these are TRUE FUNCTIONS not class methods. 
 * hard code the name of any sorting column into a function name to allow the mock tables to be sorted. 
 *
 */

function stdobj_sort_numeric($a, $b){ 
	if((int) $a > (int) $b):
		$cmp = 1;
	elseif ((int) $a < (int) $b):
		$cmp = -1;
	else:
		$cmp = 0;
	endif;
	return $cmp; 
}

function stdobj_sort_id($a, $b){ return stdobj_sort_numeric($a->id, $b->id); }
function stdobj_sort_user($a, $b){ return stdobj_sort_numeric($a->user, $b->user); }
function stdobj_sort_sort($a, $b){ return stdobj_sort_numeric($a->sort, $b->sort); }
function stdobj_sort_order($a, $b){ return stdobj_sort_numeric( $a->order, $b->order); } 
function stdobj_sort_name($a, $b){ return strcasecmp($a->name, $b->name); } 