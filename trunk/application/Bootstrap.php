<?php

class Bootstrap extends Zend_Application_Bootstrap_Base
{

    public function setupView()
    {
        // Initialise Zend_Layout's MVC helpers
        Zend_Layout::startMvc(array('layoutPath' => APPLICATION_PATH . "/layouts"));
        // TODO: add plugin for custom layout choosing
        
        // VIEW SETUP - Initialize properties of the view object
        // The Zend_View component is used for rendering views. Here, we grab a "global" 
        // view instance from the layout object, and specify the doctype we wish to 
        // use. In this case, XHTML1 Strict.
        $view = Zend_Layout::getMvcInstance()->getView();
        //$view->addHelperPath(LIB_DIR . '/ZPress/View/Helper', 'ZPress_View_Helper');
        $view->doctype('XHTML1_STRICT');
    }

    public function run()
    {
    	$this->setupView();
        $this->bootstrap('frontController');
		$this->frontController->dispatch();		
    }



}


