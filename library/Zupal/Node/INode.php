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
	const STATUS_HOMEPAGE = 2;
	const STATUS_BANNED = 4;
	const STATUS_FLAGGED = 8;
	const STATUS_ARCHIVED = 16;
	const STATUS_DELETED = 32;
	
}
