<?

class Zupal_Logs_SearchForm
extends Zend_Form
{

/* @@@@@@@@@@@@@@@@@@ constructor @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function __construct()
	{
		$ini = new Zend_Config_Ini(dirname(__FILE__) . DS . 'SearchForm.ini', 'fields');
		parent::__construct($ini);

		$this->set_module_options();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_module_options @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return void
	*/
	public function set_module_options ()
	{
		foreach(Zupal_Module_Manager::getInstance()->getModuleNames() as $module):
			$this->search_module->addMultiOption($module, $module);
		endforeach;
	}
}