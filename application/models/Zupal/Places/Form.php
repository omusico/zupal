<?php

class Zupal_Places_Form
extends Zend_Form
{
	public function __construct(Zupal_Content $pContent = NULL)
	{
		if (is_null($pContent)) $pContent = new Zupal_Content();
		
		$ini_path = dirname(__FILE__) . DS . 'form.ini';
		$config = new Zend_Config_Ini($ini_path, 'fields');
		$this->setMethod('post');
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ content @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_content = null;
	/**
	 * @return Zupal_Content;
	 */
	public function get_content() {
		if (is_null($this->_content))
		{
			$this->_content = new Zupal_Places();
			$this->fields_to_content();
		}
		
		return $this->_content; 
	}

	/**
	 * Note -- to prevent recursion this method does NOT check the existence of _content.
	 */
	public function fields_to_content()
	{
	}

	public function set_content($pValue) { $this->_content = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ content_to_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return void
	*/
	public function content_to_fields ()
	{
	}
}