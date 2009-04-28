<?php

Interface Zupal_Menu_IMenu
{

	public function __construct($pTitle = '', $pData = NULL);
	
	public function set_item($pItem, $pID = NULL);

	/**
	 * returns the menu's HTML markup as a string.
	 */
	public function __toString();
}