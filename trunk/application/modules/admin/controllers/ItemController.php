<?

class Admin_ItemController
extends Zupal_Controller_Abstract
{
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function indexAction ()
	{
		$this->_forward('create');
	}
	
	function createAction()
	{
		$this->view->modules = array();
		foreach (Zupal_Module_Manager::getInstance()->get_all() as $module_item):
			if ($module_item->info()->admin && $module_item->info()->admin->create):
				$this->view->modules[] = $module_item;
			endif;
		endforeach;		
	}
}