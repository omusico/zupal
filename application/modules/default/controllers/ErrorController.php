<?php

class ErrorController extends Zupal_Controller_Abstract
{

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        
        switch ($errors->type) { 
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error 
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }
        
        $this->view->exception = $errors->exception;
        $this->view->request   = $errors->request;
    }


/* @@@@@@@@@@@@@@@@@@@@@@ EXTENSION BOILERPLATE @@@@@@@@@@@@@@@@@@@@@@@ */

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controller_dir @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * This boilerplate should work with any controller
     *
     */
    private $_controller_dir = NULL;
    function controller_dir($pReload = FALSE) {
        if ($pReload || is_null($this->_controller_dir)):
        // process
            $this->_controller_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        endif;
        return $this->_controller_dir;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controller_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    private $_controller_name = NULL;
    function controller_name($pReload = FALSE) {
        if ($pReload || is_null($this->_controller_name)):
        // process
            if (preg_match('~^([\w)_)?([\w]+)Controller$~', get_class($this), $m)):
                $value = $m[1];
            endif;
            $this->_controller_name = $value;
        endif;
        return $this->_controller_name;
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ module_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    private $_module_name = NULL;
    function module_name($pReload = FALSE) {
        if ($pReload || is_null($this->_module_name)):
        $value = array_shift(split('_', get_class($this))) . DIRECTORY_SEPARATOR;
        // process
            $this->_module_name = $value;
        endif;
        return $this->_module_name;
    }


}



