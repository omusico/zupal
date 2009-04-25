<?php

class Zupal_Node_Abstract
extends Zupal_Domain_Abstract
implements Zupal_Node_INode
{

	public function save()
	{
		$this->node(); // assures a node record associates with this one
		parent::save();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ node @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	 * returns the identity of the node attached to this object.
	 * @return scalar
	 */

	public function nodeId()
	{
		return $this->node_id;
	}

	/**
	 * returns the DOMAIN OBJECT of the node attached to this object.
	 *
	 * @var Zupal_Nodes
	 */
	private $_node = NULL;
	function node($pReload = FALSE)
	{
		if ($pReload || is_null($this->_node)):
			if ($this->nodeId())
			{
				$this->_node = new Zupal_Nodes($this->nodeId());

			}
			else
			{
				$this->_node = new Zupal_Nodes();
				$this->_node->save();
				$this->_node_id = $this->_node->identity();
			}
		// process
		endif;
		return $this->_node;
	}

}