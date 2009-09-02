<?php

class Administer_Form_Addlistaction
extends Zupal_Form_Abstract {

    public function __construct($pAction = NULL) {
        $ini_path = preg_replace('~php$~', 'ini', __FILE__);
        $config = new Zend_Config_Ini($ini_path, 'fields');
        parent::__construct($config);

        if ($pAction):
            $this->setAction($pAction);
        endif;
    }

    public function domain_to_fields() {
    }

    public function fields_to_domain() {
    }

    protected function get_domain_class() {
        return "no_class";
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function domain_fields () {
        return array();
    }
}