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
	
	public function tableName()
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
	
	public function idField()
	{
		return $this->_id_field;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_row @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public function getRow($pID)
	{
		$rows = $this->find($pID);
		if (! $rows->current()):
			throw new Exception(__CLASS__ . ": cannot find id $pID; " . __METHOD__);
		
		endif;
		
		return $rows->current();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_all_rows @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public function allRows($pSort = NULL, $pID_only = FALSE)
	{
		if ($pID_only)
		{
			$order = $pSort ? $pSort : $this->_id_field;
			$sql = sprintf("SELECT `%s` FROM %s ORDER BY %s", $this->_id_field, $this->_name, $order);
			$rows = $this->getAdapter()->fetchCol($sql);
		} else {
			$rows = $this->fetchAll(NULL, $pSort);
		}
		
		return array_values($rows);
	}

}
