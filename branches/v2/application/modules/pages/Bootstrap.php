<?php

class Pages_Bootstrap extends Zend_Application_Module_Bootstrap
{
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _initHelpers @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param <type>
 * @return <type>
 */
    public function _initHelpers () {
/**
 * @var Zend_View
 */
        $view = Zend_Registry::get('view');
        $module = Administer_Model_Modules::getInstance()->get('pages');
        $helper_path = $module->module_path('views/helpers');
        $view->addHelperPath($helper_path, 'Pages_View_Helper');
    }
    
}