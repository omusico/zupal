<?php

class Zupal_Controller_Plugin_Layout extends Zend_Controller_Plugin_Abstract {

/**
 * Called before Zend_Controller_Front enters its dispatch loop.
 *
 * @param Zend_Controller_Request_Abstract $request
 * @return void
 */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
        $layout_route = $request->getParam('_layout', null);
        $layout_param = $request->getParam('layout', $layout_route);
        if (!is_null($layout_param)) {
            Zend_Layout::getMvcInstance()->setLayout($layout_param);
        }
    }

}
