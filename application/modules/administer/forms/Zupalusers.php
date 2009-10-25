<?php

class Administer_Form_Zupalusers extends Zupal_Form_Abstract {

    public function __construct($pDomain) {
        $ini_path = preg_replace('~php$~', 'ini', __FILE__);
        $config = new Zend_Config_Ini($ini_path, 'fields');
        parent::__construct($config);
        
        $this->load_roles();

        if ($pDomain):
            $this->set_domain($pDomain);
            $this->domain_to_fields();
        endif;
    }

    public function domain_fields() {
        return array("id","username","email","role","status");
    }


    protected function get_domain_class() {
        return "Model_Zupalusers";
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_roles @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     *
     * @return void
     */
    public function load_roles () {
        $options = array(0 => '(none)');

        foreach(Model_roles::getInstance()->find_all() as $res):
            $options[$res->identity()] = $res->title;
        endforeach;

        $this->role->setMultiOptions($options);
    }
}

