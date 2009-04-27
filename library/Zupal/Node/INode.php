<?php
/* 
 * This defines the requirement of a domain object that is linked to a node.
 */

/**
 *
 * @author daveedelhart
 */
interface Zupal_Node_INode {
	/**
	 * @return int
	 */
	public function nodeId();

	public function get_by_node($pNode_id);

	public function made($pFormat = NULL);

	public function status($pAs_array = TRUE);

	const STATUS_LIVE = 1;
	const STATUS_FRONTPAGE = 2;
	const STATUS_ARCHIVED = 4;
	const STATUS_STICKY = 8;
	const STATUS_FLAGGED = 16;
	
}
