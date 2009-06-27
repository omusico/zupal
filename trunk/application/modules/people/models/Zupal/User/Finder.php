<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Zupal_User_Finder
extends Zend_Form
{
	public function __construct()
	{
		$ini = new Zend_Config_Ini(dirname(__FILE__) . DS . 'Finder.ini', 'fields');
		parent::__construct($ini);
		//@TODO: country list?
	}
	
}