<?php

class Zupal_User_Form
extends Zupal_Form_Abstract
{
	public function __construct(Zupal_People $pPeople = NULL)
	{
		
		$ini_path = dirname(__FILE__) . DS . 'Form.ini';
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ isValid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param array $pFields
	* @return boolean
	*/
	public function isValid ($pFields)
	{
		if (!parent::isValid($pFields)) return FALSE;

		if ($this->name_first->getValue() || $this->username->getValue() || $this->name_last->getValue() || $this->email->getValue()) return true;

		$this->name_first->addErrorMessage('Must have this OR');
		$this->name_last->addErrorMessage('Must have this OR');
		$this->username->addErrorMessage('Must have this OR');
		$this->email->addErrorMessage('Must have this');

		return false;
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
		return array('name_first', 'name_last', 'email', 'username', 'gender', 'title', 'password');
	}
}