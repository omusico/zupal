<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        $config = new Zend_Config_ini(dirname(__FILE__) . '/admin_pages.ini', 'pages');
        error_log(__METHOD__ . ': cont pages = ' . print_r($config->toArray(), 1));
        $this->view->placeholder('controller_pages')->set(new Zend_Navigation(
            $config));
    }

    public function indexAction()
    {
        // action body
    }


}

