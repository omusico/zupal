<?php

class Administer_Form_Zupalmenus extends Zupal_Form_Abstract
{

    public function __construct($pDomain)
    {
        $ini_path = preg_replace('~php$~', 'ini', __FILE__);
        $config = new Zend_Config_Ini($ini_path, 'fields');
        parent::__construct($config);

        if ($pDomain):
            $this->set_domain($pDomain);
            $this->domain_to_form();
        endif;

        $this->load_resources();
        $this->load_modules();
    }

    public function domain_fields()
    {
        return array("id","name","label","created_by_module","resource","parent","module","controller","action","href","callback_class","parameters","if_module","if_controller","sort_by");
    }

    protected function get_domain_class()
    {
        return "Administer_Model_Zupalmenus";
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_resources @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     *
     * @return <type>
     */
    public function load_resources () {
        $options = array(0 => '(none)');

        foreach(Model_Resources::getInstance()->find_all() as $res):
            $options[$res->identity()] = $res->title;
        endforeach;

        $this->resource->setMultiOptions($options);
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

        $this->module->setMultiOptions($options);
    }
}

