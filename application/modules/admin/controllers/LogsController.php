<?

class Admin_LogsController
extends Zupal_Controller_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function indexAction ()
	{
	}

	public function searchvalidateAction()
	{
		$params = $this->_getAllParams();
		extract($params);
		foreach($params as $k => $v) $this->view->$k = $v;
		
		if($search_module):
			$module_list = array(Zupal_Module_Manager::getInstance()->get($search_module));
		else:
			$module_list = Zupal_Module_Manager::getInstance()->get_all();
		endif;
		$hits = array();
		foreach($module_list as $module):
			$hits = array_merge($hits, $module->search_logs($phrase, $lines));
		endforeach;

		$this->view->hits = $hits;


		$log_form = new Zupal_Logs_SearchForm();
		$log_form->isValid($params);
		$log_form->setAction(ZUPAL_BASEURL . DS . join(DS, array('admin','logs', 'searchvalidate')));
		$this->view->form = $log_form;
	}
}