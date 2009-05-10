<?php 

class Admin_ModulesController extends Zupal_Controller_Abstract
{
	
	public function indexAction() 
	{
		$moduleConfigs = array();
	

		$this->view->modules = Zupal_Module_Manager::getInstance()->get_all();
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