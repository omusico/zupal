<?php

class Administer_Bootstrap extends Zend_Application_Module_Bootstrap
{

    protected function _initLibrary()
    {
        return  new Zend_Loader_Autoloader_Resource(array(
            'basePath'      => dirname(__FILE__) . '/library',
            'namespace'     => 'Administer',
            'resourceTypes' => array(
                'base' => array(
                    'namespace' => 'Meta',
                    'path'      => 'Meta/'
                )
            ),
        ));
    }
    
}