<?php

/**
 * A classic heirarchy member.
 * Note that members fo the tree are not necessarily nodes -- or anything, really -- unless
 * a node interface is also present.
 *
 * Members of this pattern can have only one parent.
 * @author daveedelhart
 */
interface Zupal_Interface_TreeMember {
    
	/**
	 * @return Zupal_Interface_Treemember
	 */
	public function parent();

	/**
	 * returns an array of supernodes, from the root to this items' parent.
	 * Does not include this item; so the top node will return an empty array.
	 *
	 * @return Zupal_Interface_Treemember[]
	 */
	public function parents();

	/**
	 * the count of the parents(); 0..?
	 */
	public function depth();

	/**
	 * returns an array of sub-members, ordered by treeOrder();
	 * The members must be unique.
	 *
	 * @return Zupal_Interface_TreeMember[]
	 */
	public function children();

	/**
	 * @return boolean
	 */
	public function has_parent();

	/**
	 * returns the COUNT of the children.
	 * @return int;
	 */
	public function has_chilren();

	/**
	 * A scalar that can be used to sort this member with its siblings.
	 * Can be any scalar: int, string.
	 * This will be used to define an array key used by ksort() to order siblings. 
	 */
	public function tree_order();

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ siblings @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	/**
	 * returns an ordered array of siblings, including this item.
	 * @return Zupal_Interface_TreeMember[]
	 */
	public function siblings();

	/**
	 * returns previous sibling, or NULL.
	 * @return Zupal_Interface_Treemember | NULL
	 */
	public function prev_sibling();

	/**
	 * returns next sibling, or NULL.
	 * @return Zupal_Interface_Treemember | NULL
	 */
	public function next_sibling();
	
}
