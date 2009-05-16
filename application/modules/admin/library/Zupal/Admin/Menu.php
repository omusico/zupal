<?

class Zupal_Admin_Menu
extends Zupal_Menu
{

/* @@@@@@@@@@@@@@@@@@ constructor @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public function __construct($pTitle = '', $pData = NULL)
	{
		$config =  new Zend_Config_Xml(realpath(dirname(__FILE__) . '/../../../menu.xml'));
		parent::__construct('', $config->menu);

		foreach(Zupal_Module_Manager::getInstance()->get_all() as $item):
			if ($item->has_admin()):
				$this->set_item($item->admin_menu_item());
			endif;
		endforeach;
	}

}