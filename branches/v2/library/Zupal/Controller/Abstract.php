<?php

abstract class Zupal_Controller_Abstract extends Zend_Controller_Action {
    protected $security = 0;
    protected $insecure = FALSE;
    protected $identity = NULL;
    
    public function init() {
        
        $module = $this->getRequest()->getModuleName();
        $config = array(
            'basePath' => APPLICATION_PATH . '/library/' . $module,
            'namespace' => ucfirst($module)
        );
        
        $loader = new Zend_Loader_Autoloader_Resource($config);
        
        $this->view->addHelperPath(APPLICATION_PATH . '/modules/default/views/helpers/Zupal', 'Zupal_Helper');
        $this->view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
        
        parent::init();
    }
    
    public function preDispatch() {
        $message = $this->_getParam('message', '');
        $error = $this->_getParam('error', '');
        if ($message || $error):
            $this->view->placeholder('message')->set($message);
            $this->view->placeholder('error')->set($error);
        endif;
    }
    
    public function postDispatch() {
        $this->view->headTitle()->set($this->view->placeholder('page_title'));
    }
    
        /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _store @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pID, $pData, $pLabel
     * @return void
     */
    public function _store ($pID, $pData, $pLabel) {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $zd = new Zend_Dojo_Data($pID, $pData, $pLabel);
        echo $zd->toJson();
    }

    protected function _getAllParams()
    {
        return Zupal_Util_Array::stripslashes($this->getRequest()->getParams());
    }
}
