<?php 

class Admin_ModulesController extends Zend_Controller_Action 
{
	
	public function indexAction() {
		
		$manager = new Zupal_Module_Manager();
		$moduleNames = $manager->getModuleNames();
		Zend_Debug::dump($moduleNames, "ModuleNames:");
		
	}
}