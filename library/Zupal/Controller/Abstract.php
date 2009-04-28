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
		
		$active_module = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();

		foreach(Zupal_Module_Manager::getInstance()->getModuleNames() as $module)
		{
			$item = new Zupal_Menu_Item(ucfirst($module), $module, 'index', 'index');
			$menu->set_item($item);
			if (!strcasecmp($module, $active_module)):
				$item->list_class = 'active';
				$module_def = Zupal_Module_Manager::getInstance()->get($module);
				$menu_file = $module_def->info()->menu;
				if ($menu_file):
					$menu_path = $module_def->directory() . DS . $menu_file;
					if (file_exists($menu_path)):
						$config = FALSE;
						switch (pathinfo($menu_path, PATHINFO_EXTENSION )):
							case 'xml':
								$config = new Zend_Config_Xml($menu_path, 'menu');
								break;

							case 'ini';
								$config = new Zend_Config_Ini($menu_path);
								break;

						endswitch;
						
						if ($config):
							$submenu = new Zupal_Menu('', $config);
							$item->submenu = $submenu;
						endif;
					endif;
				endif;
			endif;
		}

		$this->view->placeholder('nav')->set($menu);
	}
}
