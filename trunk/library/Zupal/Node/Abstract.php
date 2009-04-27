<?php

abstract class Zupal_Node_Abstract
extends Zupal_Domain_Abstract
implements Zupal_Node_INode,
	Zupal_Content_IContent
{

	public function save()
	{
		$this->node();
		parent::save();
		if ($this->_is_versioned):
			$this->node()->version = $this->identity();
			$this->node()->save();
		endif;
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
		if ($pReload || is_null($this->_node)):
			if ($this->nodeId())
			{
				$this->_node = new Zupal_Nodes($this->nodeId());
				$this->_node->table = $this->table()->tableName();
				$this->_node->class = get_class($this);
				$this->_node->save();
			}
			else
			{
				$this->_node = new Zupal_Nodes();
				$this->_node->save();
				$this->{$this->node_field()} = $this->_node->identity();
			}
		// process
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

/**
 *
 * @param array $searchCrit
 * @param string $sort
 * @return Zend_Db_Table_Select
 */
	protected function _select(array $searchCrit = NULL, $sort = NULL)
	{
		$table = $this->table();
		$id_field = $table->idField();

		// create select for finding node ids
		if ($this->_is_versioned):
		$select = $table->getAdapter()->select()
			->from($table->tableName()); // do not include node table
		else:
			$select = $table->select();
		endif;

		if (is_array($searchCrit) && count($searchCrit)):
			foreach($searchCrit as $crit):
				if (is_array($crit)):
					call_user_func_array(array($select, 'where'), $crit);
				else:
					$select->where($crit);
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

	public function find(array $searchCrit = NULL, $sort = NULL)
	{
		$table = $this->table();
		$id_field = $table->idField();

		$select = $this->_select($searchCrit, $sort);

		if ($this->_is_versioned):

			$node_stub = Zupal_Nodes::getInstance();

			$cond = sprintf('( `%s`.%s = `%s`.node_id )', $table->tableName(), $this->node_field(), $node_stub->table()->tableName());
			$cond .= sprintf(' AND (`%s`.%s = `%s`.version)', $table->tableName(), $id_field, $node_stub->table()->tableName());
			//@TODO: cache this expression?
			$select->join($node_stub->table()->tableName(), $cond, array());
			$rows = $table->getAdapter()->fetchAll($select, array(), Zend_Db::FETCH_OBJ);
		else:
			
			$rows = $table->fetchAll($select);
		endif;

		error_log(__METHOD__ . ': ' . $select->assemble());

		// transfer data into domain objects.
		$domain_objects = array();
		foreach($rows as $row) $domain_objects[] = $this->get($row);

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
	public function findOne(array $searchCrit = NULL, $sort = NULL)
	{
		$table = $this->table();
		$id_field = $table->idField();
		$node_field = $this->node_field();

		$select = $this->_select($searchCrit, $sort);

		if ($this->_is_versioned):

			$node_stub = Zupal_Nodes::getInstance();

			$cond = sprintf('( `%s`.%s = `%s`.node_id )', $table->tableName(), $this->node_field(), $node_stub->table()->tableName());
			$cond .= sprintf(' AND (`%s`.%s = `%s`.version)', $table->tableName(), $id_field, $node_stub->table()->tableName());
			//@TODO: cache this expression?
			$select->join($node_stub->table()->tableName(), $cond, array());
			$row = $table->getAdapter()->fetchOne($select);
			$id = $row[$id_field];
		else:
			$row = $this->table()->fetchRow($select);
			$id = $row->$id_field;
		endif;

		error_log(__METHOD__ . ': ' . $select->assemble());
		
		// transfer data into domain objects.
		return $this->get($id);
	}

	public function findAll($pSort = NULL){
		return $this->find(NULL, $pSort);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ status @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $STATUSES = array(Zupal_Node_INode::STATUS_ARCHIVED,
		Zupal_Node_INode::STATUS_FLAGGED,
		Zupal_Node_INode::STATUS_FRONTPAGE,
		Zupal_Node_INode::STATUS_LIVE,
		Zupal_Node_INode::STATUS_STICKY);

	private static $STATUS_PHRASES =  array(Zupal_Node_INode::STATUS_ARCHIVED => 'Archived',
		Zupal_Node_INode::STATUS_FLAGGED => 'Flagged',
		Zupal_Node_INode::STATUS_FRONTPAGE => 'Frontpage',
		Zupal_Node_INode::STATUS_LIVE => 'Live',
		Zupal_Node_INode::STATUS_STICKY => 'Sticky');
	/**
	*
	* @param boolean $pAs_array
	* @return boolean | array
	*/
	public function status ($pAs_array = FALSE)
	{
		$status = (int) $this->node()->status;
		$out = array();

		if ($pAs_array == 2):
			foreach(self::$STATUS_PHRASES as $s => $phrase):
				if ($s & $status) $out[$s] = $phrase;
			endforeach;
		elseif ($pAs_array):
			foreach(self::$STATUSES as $s):
				if ($s & $status) $out[] = $s;
			endforeach;
		else:
			$out = $status;
		endif;
		return $out;
	}

	public function is($pStatus)
	{
		return $pStatus & $this->status();
	}
}