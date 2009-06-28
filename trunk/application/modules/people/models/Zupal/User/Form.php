<?php

class Zupal_User_Form
extends Zupal_Form_Abstract
{
	public function __construct($pUser = NULL)
	{
		
		
		$root = Zend_Controller_Front::getInstance()->getBaseUrl() . DS . 'people' . DS . 'users';

		if (is_numeric($pUser)) {
			$pUser = new Zupal_Users($pUser);
		}
		elseif (is_null($pUser))
		{
			$pUser = new Zupal_Users();
		}
		
		if ($pUser->identity())
		{
			$this->setAction($root . DS . 'updatevalidate');
		}
		else
		{
			$this->setAction($root . DS . 'addvalidate');
			$this->submit->setLabel('Create User');
		}
		
		$ini_path = dirname(__FILE__) . DS . 'Form.ini';
		$config = new Zend_Config_Ini($ini_path, 'fields');
		$this->setMethod('post');
		parent::__construct($config);
		$this->set_domain($pUser);

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
		return array('person_id', 'name_first', 'name_last', 'email', 'username', 'gender', 'title', 'password');
	}
}