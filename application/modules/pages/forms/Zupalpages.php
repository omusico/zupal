<?php

class Pages_Form_Zupalpages extends Zupal_Form_Abstract
{

    public function __construct($pDomain)
    {
        $ini_path = preg_replace('~php$~', 'ini', __FILE__);
        $config = new Zend_Config_Ini($ini_path, 'fields');
        parent::__construct($config);

        $this->_init_resources_menu();
        $this->_init_status_menu();

        if ($pDomain):
            $this->set_domain($pDomain);
            $domain = $this->get_domain();

            $this->domain_to_fields();
            $this->title->setValue($domain->get_title());
            $this->lead->setValue($domain->get_lead());
            $this->content->setValue($domain->get_content());
        endif;
    }

    public function domain_fields()
    {
        return array("id","atomic_id","resource","publish_status");
    }

    protected function get_domain_class()
    {
        return "Pages_Model_Zupalpages";
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _init_resources_menu @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    protected function _init_resources_menu () {
        foreach(Model_Resources::getInstance()->findAll('resource_id') as $resource):
            $this->resource->addMultiOption($resource->identity(), $resource->title);
        endforeach;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _init_status_menu @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    protected function _init_status_menu () {
        foreach(Pages_Model_Zupalpagestatuses::getInstance()->findAll('rank') as $status):
            $this->publish_status->addMultiOption($status->identity(), $status->title);
        endforeach;
    }

    public function save()
    {
        $this->get_domain()->setTitle($this->title->getValue());
        $this->get_domain()->setLead($this->lead->getValue());
        $this->get_domain()->setContent($this->content->getValue());
        $this->get_domain()->save();

    }
}

