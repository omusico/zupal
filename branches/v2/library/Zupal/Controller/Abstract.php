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
            $pages = APPLICATION_PATH . '/modules/' .  $module . '/views/pages.ini';

            if (file_exists($pages)):
                $module_pages = new Zend_Config_Ini($pages, 'module');
                if (count($module_pages)):
                    $this->view->module_pages = new Zend_Navigation($module_pages);
                    foreach($this->view->module_pages as $page):
                        if ($page instanceof Zend_Navigation_Page_Mvc):
                            if (strcasecmp($page->getController(), $this->getRequest()->getControllerName())):
                                $page->removePages();
                            endif;
                        endif;
                    endforeach;
                else:
                    $this->view->module_pages = FALSE;
                endif;
            endif;

	}

	public function postDispatch()
	{
		$this->view->headTitle($this->view->placeholder('page_title'));
	}
}
