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

        $simple_form = $this->_simple_form();

        $complex_form = $this->_complex_form();

        $config_form = Zupal_Fastform_Form::from_config(dirname(__FILE__) . '/config_form.ini', 'config_form', 'Configuration Form');

        $this->view->simple_form = $simple_form;
        $this->view->complex_form = $complex_form;
        $this->view->config_form = $config_form;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _simple_form @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return
     */
    public function _simple_form () {
        $params = array('field_width' => 500);
        $simple_form = new Zupal_Fastform_Form('simple_form', 'simple_form_id',
            'Simple Form', '/administer/tests/formexecute/form/simple', array(), $params);
        $username = new Zupal_Fastform_Field_Text('username', 'User Name', 'Bob', array(), $simple_form);
        $password = new Zupal_Fastform_Field_Text('password', 'Password', 'MyPass', array('password' => TRUE), $simple_form);
        $note = new Zupal_Fastform_Field_Text('note', 'Comment', 'This is a note', array('rows' => 3), $simple_form);
        return $simple_form;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _complex_form @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return
     */
    public function _complex_form () {
        $params = array('field_width' => 500);
        $complex_form = new Zupal_Fastform_Form('complex_form', 'complex_form_id', 'Complex Form', 
           '/administer/tests/formexecute/form/complex', array(), $params);
        $username = new Zupal_Fastform_Field_Text('username', 'User Name', 'Bob', 
            array(), $complex_form);
        $password = new Zupal_Fastform_Field_Text('password', 'Password', 'MyPass', 
            array('password' => TRUE), $complex_form);
        $note = new Zupal_Fastform_Field_Text('note', 'Comment', 'This is a note', 
            array('rows' => 3), $complex_form);

        $pData = array('red' => 'Red', 'green' => 'Green', 'blue' => 'Blue');
        $props = array('type' => Zupal_Fastform_Field_Choice::CHOICE_LIST);
        $color = new Zupal_Fastform_Field_Choice('colors', 'Colors', 'green', 
            $pData, $props, $complex_form);

        $props = array('type' => Zupal_Fastform_Field_Choice::CHOICE_CHECKBOX);
        $active = new Zupal_Fastform_Field_Choice('active', 'Active', false, 
            NULL, $props, $complex_form);

        $days = array('M' => 'Monday', 'T' => 'Tuesday', 'W' => 'Wednesday', 'Th' => 'Thursday', 'F' => 'Friday');
        $day = new Zupal_Fastform_Field_Choice('days', 'Days', 
            array('M', 'W'), $days, $props, $complex_form);

        return $complex_form;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ mergesortAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function mergesortAction () {
        $this->view->data = preg_split('/[^\d]+/', $this->_getParam('data', array()));
        $form = new Zupal_Fastform_Form('data', 'data', '/administer/test/mergesort');
        $form->set_field(new Zupal_Fastform_Field_Text('data', 'Data', join(',', $this->view->data), array('rows' => 5), $form));
        $this->view->data_form = $form;
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ switchAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function switchAction () {
    }
}

