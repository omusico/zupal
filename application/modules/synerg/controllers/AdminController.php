<?php
class Synerg_AdminController extends Zupal_Controller_Abstract {
/**
 *
 */

    public function preDispatch() {
        $u = Model_Users::current_user();
        if (!$u || ! $u->can('synerg_manage')):
            $param = array('error' => 'This area is reserved for administrators');
            return $this->_forward('insecure', 'error', 'default', $param);
        endif;
        $this->_helper->layout->setLayout('admin');
        parent::preDispatch();
    }

    public function indexAction() {
    }

/* @@@@@@@@@@@@@ EXTENSION BOILERPLATE @@@@@@@@@@@@@@ */

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controller_dir @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function controller_dir () {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }
    
}

