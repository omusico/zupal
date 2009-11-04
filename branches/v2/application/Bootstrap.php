<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
    protected function _initAutoload() {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => dirname(__FILE__) . '/modules/default',
        ));
        return $autoloader;
    }

    public function _initZendView () {

        $this->bootstrap('view');
        $view = $this->getResource('view');
        $name = get_class($view);
        Zend_Registry::set('view', $view);
        return $view;
    }

    protected function _initDefaultLoader() {

        $loader = Zend_Loader_Autoloader::getInstance();
        //$loader->registerNamespace('Coco_');
        $loader->setFallbackAutoloader(true);
        $loader->suppressNotFoundWarnings(false);

        return $loader;
    }
/*
    public function _initAdminRoute() {
        $this->_bootstrap('frontController');

        $front = $this->getResource('frontController');

        $route = new Zend_Controller_Router_Route(
            'admin/:module/:controller/:action/*',
            array('_layout' => 'admin'));
        $front->getRouter()->addRoute('admin_module_controller_action', $route);

        $route = new Zend_Controller_Router_Route(
            'admin/:module/:controller',
            array('_layout' => 'admin'));
        $front->getRouter()->addRoute('admin_module_controller', $route);

        $route = new Zend_Controller_Router_Route(
            'admin/:module',
            array('_layout' => 'admin'));
        $front->getRouter()->addRoute('admin_module', $route);

        $route = new Zend_Controller_Router_Route(
            'admin',
            array('_layout' => 'admin'));
        $front->getRouter()->addRoute('admin', $route);
    } */

    protected function _initRoutes()
    {

        $frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();
        $options = new Zend_Config_Ini(APPLICATION_PATH . DS . 'configs' . DS . 'application.ini', APPLICATION_ENV);
        $router->addConfig($options, 'routes');
    }

}

