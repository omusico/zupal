<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * Note that a place item is not necessarily a database / domain item. 
 */

/**
 *
 * @author daveedelhart
 */

interface Zupal_Place_IItem {

	public function identity();
	public function get_value();
	public function set_value($pString);
	public function __toString();
}
