<?php
/* 
 * Represents an instantiated set member --
 * an item which can associate with one or more sets.
 */

/**
 *
 * @author daveedelhart
 */
interface Zupal_Interface_SetMember {

	/**
	 * An unordered array of Zupal_Interface_Sets.
	 * @return Zupal_Interface_Set[]
	 */
	public function sets();

	/**
	 * Include this member in one OR MORE sets; should accept one or more:
	 * - setMember objects
	 * - strings (as setMember ids)
	 * - array(s) of the above (or array(s) of setMember objects AND strings)
	 */
	public function add_sets();

	/**
	 * Removes the set member from the sets passed in. See Sets for parameter options
	 */
	public function remove_sets();

	/**
	 * changes this member to belong exclusively to the sets passed in.
	 * Can accept an empty array (or no parameters) to remove all set associations
	 */
	public function define_sets();
}
