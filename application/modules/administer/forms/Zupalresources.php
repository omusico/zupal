<?php

class Administer_Form_Zupalresources
extends Zupal_Form_Abstract {

    public function __construct($pDomain) {
        $ini_path = preg_replace('~php$~', 'ini', __FILE__);
        $config = new Zend_Config_Ini($ini_path, 'fields');
        parent::__construct($config);

        if ($pDomain):
            $this->set_domain($pDomain);
        endif;
        $domain_values = $this->get_domain()->toArray();
        
        $this->load_modules();
    }

    public function domain_fields() {
        return array("resource_id","title","rank");
    }

    protected function get_domain_class() {
        return "Model_Resources";
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_resources @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     *
     * @return <type>
     */
    public function load_modules () {
        $options = array(0 => '(none)');

        foreach(Administer_Model_Modules::getInstance()->find_all() as $res):
            $options[$res->identity()] = $res->title();
        endforeach;

        $this->resourcemodule->setMultiOptions($options);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ fields_to_domain @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pFields = NULL
     * @return void
     */
    public function fields_to_domain (array $pFields = NULL) {
        $this->get_domain()->module = $this->resourcemodule->getValue();
        parent::fields_to_domain($pFields);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain_to_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pFields
     * @return void
     */
    public function domain_to_fields (array $pFields = NULL) {
        $this->resourcemodule->setValue($this->get_domain()->module);
        parent::domain_to_fields($pFields);
    }
}

