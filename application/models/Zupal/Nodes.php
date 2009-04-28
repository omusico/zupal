<?php

/**
 * this class represents the domain class for the nodes table.
 * as such it does NOT implement Zupal_Node_INode, but it is a component
 * of any domain that implements Zupal_Node_INode.
 */

class Zupal_Nodes Extends Zupal_Domain_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * @see CPF_Formset_Domain::get_table_class()
	 *
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
	 */
	public function get ($pID)
	{
		return new Zupal_Nodes($pID);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_Instance = NULL;
	/**
	 *
	 * @param boolean $pReload
	 * @return Zupal_Nodes
	 */
	static function getInstance($pReload = FALSE)
	{
		if ($pReload || is_null(self::$_Instance)):
		// process
		self::$_Instance = new Zupal_Nodes(Zupal_Domain_Abstract::STUB);
		endif;
		return self::$_Instance;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ status @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $STATUSES = array(Zupal_Node_INode::STATUS_ARCHIVED,
		Zupal_Node_INode::STATUS_DELETED,
		Zupal_Node_INode::STATUS_FLAGGED,
		Zupal_Node_INode::STATUS_HOMEPAGE,
		Zupal_Node_INode::STATUS_LIVE,
		Zupal_Node_INode::STATUS_BANNED
	);

	public static $STATUS_PHRASES =  array(Zupal_Node_INode::STATUS_ARCHIVED => 'Archived',
		Zupal_Node_INode::STATUS_DELETED => 'Deleted',
		Zupal_Node_INode::STATUS_FLAGGED => 'Flagged',
		Zupal_Node_INode::STATUS_HOMEPAGE => 'Homepage',
		Zupal_Node_INode::STATUS_LIVE => 'Live',
		Zupal_Node_INode::STATUS_BANNED => 'Banned'
	);
	/**
	*
	* @param boolean $pAs_array
	* @return boolean | array
	*/
	public function status ($pOverride_if_deleted = TRUE)
	{
		$status = (int) $this->status;
		$out = array();

		foreach(self::$STATUS_PHRASES as $s => $phrase):
			if ($s & $status) $out[$s] = $phrase;
		endforeach;
		// apply deleted overrides
		if ($pOverride_if_deleted):
			if (Zupal_Node_INode::STATUS_DELETED & $status):
				unset($out[Zupal_Node_INode::STATUS_LIVE]);
				unset($out[Zupal_Node_INode::STATUS_HOMEPAGE]);
			endif;
		endif;
		return $out;
	}

	public function is($pStatus, $pOverride_if_deleted = TRUE)
	{
		if ($pOverride_if_deleted && (Zupal_Node_INode::STATUS_DELETED & $pStatus)
			&& (($pStatus == Zupal_Node_INode::STATUS_HOMEPAGE) || ($pStatus == Zupal_Node_INode::STATUS_LIVE))):
			return FALSE;
		endif;

		return $pStatus & $this->status;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_status @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param array|int $pStatus
	* @return int;
	*/
	public function set_status ( $pStatus)
	{
		if (is_array($pStatus)):
			$this->status = array_sum($pStatus);
		else:
			$this->status = intval($pStatus);
		endif;
		return $this->status;
	}
}