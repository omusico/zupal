<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
    protected function _initAutoload() {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Xtract_',
            'basePath'  => dirname(__FILE__) . '/default',
        ));
        return $autoloader;
    }

    protected function _initDefaultLoader() {

        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->registerNamespace('Xtractlib_');
        $loader->setFallbackAutoloader(true);
        $loader->suppressNotFoundWarnings(false);

        return $loader;
    }

}

