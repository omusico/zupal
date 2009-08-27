<?php

abstract class Zupal_Controller_Abstract extends Zend_Controller_Action
{
	protected $security = 0;
	protected $insecure = FALSE;
	protected $identity = NULL;

	public function init()
	{

	}

	public function preDispatch()
	{
            $module = $this->getRequest()->getModuleName();
            $config = array(
                'basePath' => APPLICATION_PATH . '/library/' . $module,
                'namespace' => ucfirst($module)
            );

            $loader = new Zend_Loader_Autoloader_Resource($config);

            $this->view->addHelperPath(APPLICATION_PATH . '/modules/default/views/helpers/Zupal', 'Zupal_Helper');
	}

	public function postDispatch()
	{
		$this->view->headTitle($this->view->placeholder('page_title'));
	}
}
