<?php

class Zupal_People_Form
extends Zend_Form
{
	public function __construct(Zupal_People $pPeople = NULL)
	{
		if (is_null($pPeople)) $pPeople = new Zupal_People();
		
		$ini_path = dirname(__FILE__) . DS . 'form.ini';
		$config = new Zend_Config_Ini($ini_path, 'fields');
		
		parent::__construct($config);

		$root = Zend_Controller_Front::getInstance()->getBaseUrl() . DS . 'people' . DS . 'item';

		if ($pPeople->identity())
		{
			$this->set_people($pPeople);
			$this->people_to_fields();
			$this->setAction($root . DS . 'updatevalidate');
		}
		else
		{
			$this->setAction($root . DS . 'addvalidate');
			$this->submit->setLabel('Create Person');
			$this->set_people($pPeople);
		}
		$this->setMethod('post');
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ people @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_people = null;
	/**
	 * @return Zupal_People;
	 */
	public function get_people() {
		if (is_null($this->_people))
		{
			$this->_people = new Zupal_People();
		}
		
		return $this->_people;
	}

	/**
	 * Note -- to prevent recursion this method does NOT check the existence of _people.
	 */
	public function fields_to_people()
	{
	}

	public function set_people($pValue) { $this->_people = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ people_to_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return void
	*/
	public function people_to_fields ()
	{

	}
}