<?php

class Zupal_Modules_Form
extends Zupal_Form_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __construct @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pModel = NULL
	* @return <type>
	*/
	public function __construct ($pModel = NULL)
	{
		if (is_null($pModel)) $pModel = new Zupal_Modules();
		parent::__construct(new Zend_Config_Ini(dirname(__FILE__) . DS . 'Form.ini', 'fields'));
		$this->set_domain($pModel);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function domain_fields ()
	{
		return array('name', 'description', 'enabled', 'required', 'version', 'menu', 'package', 'made');
	}

}