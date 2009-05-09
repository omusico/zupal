<?php

class Zupal_People_Form
extends Zupal_Form_Abstract
{
	public function __construct(Zupal_People $pPeople = NULL)
	{
		
		$ini_path = dirname(__FILE__) . DS . 'form.ini';
		$config = new Zend_Config_Ini($ini_path, 'fields');
		
		parent::__construct($config);

		$root = Zend_Controller_Front::getInstance()->getBaseUrl() . DS . 'people' . DS . 'item';

		if (is_null($pPeople)) $pPeople = new Zupal_People();
		$this->set_domain($pPeople);
		
		if ($pPeople->identity())
		{
			$this->setAction($root . DS . 'updatevalidate');
		}
		else
		{
			$this->setAction($root . DS . 'addvalidate');
			$this->submit->setLabel('Create Person');
		}
		
		$this->setMethod('post');
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param int $pID
	* @return Zupal_Domain
	*/
	public static function make($pID)
	{
		return new self(new Zupal_People($pID));
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function domain_fields ()
	{
		return array('name_first', 'name_last', 'email');
	}
}