<?php

abstract class Zupal_Controller_Abstract extends Zend_Controller_Action
{
	protected $security = 0;
	protected $insecure = FALSE;
	protected $identity = NULL;

	public function init()
	{
		$this->view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
		$layout = $this->_helper->layout;
		$root = realpath(dirname(__FILE__) . '/../../../');
		$layout->setLayoutPath(LAYOUT_PATH);
		$layout->setLayout('default');
		foreach(array('message', 'error') as $field)
		if ($this->_hasParam($field)):
			$this->view->$field = $this->_getParam($field);
		endif;
		$this->view->placeholder('base_path') ->set($this->getFrontController()->getBaseUrl());
	}

	public function preDispatch()
	{
		$menu = new Zupal_Menu('Modules');

		foreach(Zupal_Module_Manager::getInstance()->getModuleNames() as $module)
		{
			$item = new Zupal_Menu_Item(ucfirst($module), $module, 'index', 'index');
			$menu->set_item($item);
		}

		$this->view->placeholder('nav')->set($menu);
	}
}
