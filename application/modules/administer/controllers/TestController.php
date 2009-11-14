<?php

class Administer_TestController extends Zupal_Controller_Abstract {

/**
 *
 */
    public function preDispatch() {
        $u = Model_Users::current_user();

        if (!$u || ! $u->can('site_admin')):
            $param = array('error' => 'This area is reserved for administrators');
            $this->_forward('insecure', 'error', 'administer', $param);

    endif;
    }

    /**
     *
     */
    public function init() {
        $this->_helper->layout->setLayout('admin');
        parent::init();
    }


    public function formAction() {

        $simple_form = new Zupal_Fastform_Form('simple_form', 'simple_form_id', 'Simple Form', '/siml;e/form');

        $username = new Zupal_Fastform_Field_Text('username', 'User Name', 'Bob', array(), $simple_form);

        $password = new Zupal_Fastform_Field_Text('password', 'Password', 'MyPass', array('password' => TRUE), $simple_form);
        
        $note = new Zupal_Fastform_Field_Text('note', 'Comment', 'This is a note', array('rows' => 3), $simple_form);

        $complex_form = '';

        $data_form = '';

        $this->view->simple_form = $simple_form;
        $this->view->complex_form = $complex_form;
        $this->view->data_form = $data_form;
    }


}

