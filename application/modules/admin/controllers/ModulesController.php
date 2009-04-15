<?php 

class Admin_ModulesController extends Zend_Controller_Action 
{
	
	public function indexAction() 
	{
		$moduleConfigs = array();
	
		$manager = new Zupal_Module_Manager();
		
		foreach($manager->getModuleNames() as $moduleName) 
		{
			$config = $manager->getModuleInfo($moduleName);
			$moduleConfigs[$moduleName] = $config->toArray();	
		}	
		
		$this->view->moduleConfigs = $moduleConfigs;
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
}