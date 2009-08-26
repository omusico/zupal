<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author daveedelhart
 */
interface Zupal_View_Ticket_ITicket {
    //put your code here
	
	public function __construct($pItem);

	public function set_title($pTitle);

	public function set_value($pName, $pValue);

	public function set_action($pLabel, $pParams);

	public function render();
}

