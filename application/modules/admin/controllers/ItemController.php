<?php 

class Admin_ModulesController extends Zupal_Controller_Abstract
{
	
	public function indexAction() 
	{
		$moduleConfigs = array();
	

		$this->view->modules = Zupal_Module_Manager::getInstance()->get_all();
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ dataAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function dataAction ()
	{
        $this->_helper->layout->disableLayout();
		$this->view->data = Zupal_Modules::getInstance()->render_data(array(), 'email');
	}
	
	public function enableAction() {}
	
	public function disableAction() {}
}