<?php
/* 
 * Represents an instantiated set item.
 */

/**
 *
 * @author daveedelhart
 */
interface Zupal_Interface_Set {
    
	
	/**
	 * A human readable label; can be equal to set_id().
	 */
	public function title();

	/**
	 * An alphanumeric identifier passing the regex "word" pattern.
	 *
	 */
	public function setId();
	
	
	/**
	 * An array of items belonging to this set. 
	 * Has no natural order, but each member listed only once. 
	 * @return Zupal_Interface_SetMember[]
	 */
	public function members();

	/**
	 *
	 * Tests a setmember to see if it belongs in this set
	 * @param Zupal_Interface_SetMember $member
	 */
	public function includes(Zupal_Interface_SetMember $member);
}
