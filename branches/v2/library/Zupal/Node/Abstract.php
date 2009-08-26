<?php
/**
 * This class represents a domain object that utilizes a node.
 * Note tha tthe node object itself is a domain object that is a compositional part of this one.
 */
abstract class Zupal_Node_Abstract
extends Zupal_Domain_Abstract
implements Zupal_Node_INode,
	Zupal_Content_IContent
{

	public function save()
	{		
		$node = $this->node();
		$node_field = $this->node_field();
		if (!$this->$node_field):
			$this->$node_field = $node->identity();
		endif;
	
		parent::save();
		
		$node->version = $this->identity();
		$node->table = $this->table()->tableName();
		$node->class = get_class($this);
		$node->save();
	}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ node @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	 * returns the identity of the node attached to this object.
	 * @return scalar
	 */

	public function nodeId()
	{
		return $this->{$this->node_field()};
	}

	protected function node_field()
	{
		return 'node_id';
	}
	/**
	 * returns the DOMAIN OBJECT of the node attached to this object.
	 *
	 * @var Zupal_Nodes
	 */
	private $_node = NULL;

	/**
	 *
	 * @param boolean $pReload
	 * @return Zupal_Nodes
	 */
	function node($pReload = FALSE)
	{
		if ($pReload || (is_null($this->_node))):
			$this->_node = new Zupal_Nodes($this->nodeId());
			$this->_node->save();
		endif;

		return $this->_node;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_by_node @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function get_by_node ($pNode_id)
	{
		$table = $this->table();
		$select = $table->select()
		->where($this->node_field() . ' = ?', $pNode_id)
		->order($table->idField() . ' DESC');
		$row = $table->fetchRow($select);
		return $this->get($row);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ made @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	* The date the node was made. You can override to refer to a data table if the table has pre-set creation dates.
	* @param string $pFormat
	* @return string
	*/
	public function made ($pFormat = NULL)
	{
		$out = $this->node()->made;
		if ($pFormat) $out = date($pFormat, $out);
		return $out;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ find @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	const LIVE_SELECT = '((status & 1) AND (NOT (status & 32)))';
/**
 *
 * @param array $searchCrit
 * @param string $sort
 * @return Zend_Db_Table_Select
 */
	protected function _select($searchCrit = NULL, $sort = NULL, $pDomain = FALSE)
	{
		if ($pDomain && ($searchCrit instanceof Zend_Db_Table_Select)):
			return $searchCrit;
		elseif ($searchCrit instanceof Zend_Db_Select):
			return $searcCrit;
		endif;		

		$table = $this->table();
		$id_field = $table->idField();
		$tn = $table->tableName();

		if ($pDomain):
			$select= $table->select();
		else:
			$select = $table->getAdapter()->select()
				->from($tn); // do not include node table
		endif;

		if (is_array($searchCrit) && count($searchCrit)):
			foreach($searchCrit as $key => $crit):
				if (is_array($crit)):
					call_user_func_array(array($select, 'where'), $crit);
				elseif (preg_match('~(=|LIKE|>|<)~', $crit) || is_numeric($key)):
					$select->where($crit);
				elseif (is_numeric($crit)):
					$select->where("$key = ?", $crit);
				else:
					$select->where("$key LIKE ?", $crit);
				endif;
			endforeach;
		endif;
		
		if (!is_null($sort)):
			$select->order($sort);
		endif;
		return $select;
	}

/**
 * because node content can be versioned, you fetch the latest qualifying versions first.
 * Note -- versioning adds a wrinkle in that older versions of the same node exist in the same table.
 * @param <type> $searchCrit
 * @param <type> $sort
 */

	protected $_is_versioned = TRUE;

	public function find_node($pNode_id)
	{
		$nf = $this->node_field();
		return $this->findOne(
			array($nf => $pNode_id),
			NULL,
			TRUE
		);
	}

	public function find($searchCrit = NULL, $sort = NULL, $pDomain = FALSE)
	{
		$table = $this->table();
		$id_field = $table->idField();
		$domain_objects = array();

		$select = $this->_select($searchCrit, $sort, $pDomain);

		if (!$pDomain):
			$node_stub = Zupal_Nodes::getInstance();

			$cond = sprintf('( `%s`.%s = `%s`.node_id )', $table->tableName(), $this->node_field(), $node_stub->table()->tableName());
			$cond .= sprintf(' AND (`%s`.%s = `%s`.version)', $table->tableName(), $id_field, $node_stub->table()->tableName());
			//@TODO: cache this expression?
			$select->join($node_stub->table()->tableName(), $cond, array());
			$sql = $select->assemble();
			$rows = $table->getAdapter()->fetchAll($select, array(), Zend_Db::FETCH_OBJ);

			// transfer data into domain objects.
			foreach($rows as $row):
				$data = $this->newRow();
				$data->setFromArray((array) $row);
				$domain_objects[] = $this->get($data);
			endforeach;
		else:
			error_log(__METHOD__ . ': ' . $select->assemble());
			$rows = $table->fetchAll($select);
			foreach($rows as $row):
				$domain_objects[] = $this->get($row);
			endforeach;
		endif;

		error_log(__METHOD__ . ': ' . $select->assemble());


		return $domain_objects;
	}

	/**
	 * returns a single record matching the search crit.
	 * If several records match the crit wil return the first one based on the sort param.
	 * Note the variation that versioning inserts into the process
	 *
	 * @param scalar[] $searchCrit
	 * @param string $sort
	 * @return Zupal_Content_IDomain
	 */
	public function findOne($searchCrit = NULL, $sort = NULL, $pDomain = FALSE)
	{
		$table = $this->table();
		$id_field = $table->idField();
		$node_field = $this->node_field();

		$select = $this->_select($searchCrit, $sort, $pDomain);

		if ($pDomain):
			$row = $table->fetchRow($select);
			return $this->get($row);
		else:
			if ($this->_is_versioned):

				$node_stub = Zupal_Nodes::getInstance();

				$cond = sprintf('( `%s`.%s = `%s`.node_id )', $table->tableName(), $this->node_field(), $node_stub->table()->tableName());
				$cond .= sprintf(' AND (`%s`.%s = `%s`.version)', $table->tableName(), $id_field, $node_stub->table()->tableName());
				//@TODO: cache this expression?
				$select->join($node_stub->table()->tableName(), $cond, array());
				$row = $table->getAdapter()->fetchRow($select);
				$id = $row[$id_field];
				// transfer data into domain objects.
				return is_null($id) ? NULL : $this->get($id);
			else:
				$row = $this->table()->fetchRow($select);
				return $this->get($row);
			endif;
		endif;

	}

	public function findAll($pSort = NULL){
		return $this->find(NULL, $pSort);
	}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ status @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return int[]
	*/
	public function status ($pOverride_if_deleted = TRUE)
	{
		return $this->node()->status($pOverride_if_deleted);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ is @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param int $pStatus
	* @return boolean
	*/
	public function is ($pStatus, $pOverride_if_deleted = TRUE)
	{
		return $this->node()->is($pStatus, $pOverride_if_deleted);
	}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __call @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pMethod, $pParams
	* @return <type>
	*/
	public function __call ($pMethod, $pParams)
	{
		if (preg_match('~^domain_(.*)~', $pMethod, $hits)):
			return call_user_func_array(array($this, $hits[1]), $pParams);
		endif;

		return parent::__call($pMethod, $pParams);
	}

}