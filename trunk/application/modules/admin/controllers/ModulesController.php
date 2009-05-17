<?php 

class Admin_ModulesController extends Zupal_Controller_Abstract
{
	public function indexAction() 
	{
		$moduleConfigs = array();
	
		Zupal_Module_Manager::getInstance()->update_database();
		$this->view->modules = Zupal_Module_Manager::getInstance()->get_all();
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ edit @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public function editAction()
	{
		$this->view->form = new Zupal_Modules_Form(new Zupal_Modules($this->_getParam('name')));
	}
	
	
	public function installAction() {}
	
	public function uninstallAction() 
	{
		$request = $this->getRequest();
		if($request->getParam('moduleName',null) != null) 
		{
			$moduleName = $request->getParam('moduleName');
			$message = sprintf("Module: %s uninstalled.", $moduleName);
			Zend_Debug::dump($message, "Uninstall Message:");
			exit();
			$this->_helper->flashMessenger($message);
		}
	}
	
	public function enableAction() {}
	
	public function disableAction() {}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ viewAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function viewAction ()
	{
		$this->view->item = Zupal_Module_Manager::getInstance()->get($this->_getParam('name'));
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ dataAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function dataAction ()
	{
        $this->_helper->layout->disableLayout();
		$this->view->data = Zupal_Modules::getInstance()->render_data(array(), 'name');
	}

}