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

            $this->domain_to_fields();
            $this->_atom_load();
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

        if ($this->get_parent()):
            if (is_numeric($this->get_parent())):
                $parent = Pages_Model_Zupalpages::getInstance()->for_atom_id($this->get_parent());
                $this->set_parent($parent);
            endif;
            $this->get_domain()->bond_to('parent', $this->get_parent(), FALSE);
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_domain @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return Pages_Model_Zupalpages
     */
    public function get_domain () {
        return parent::get_domain();
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ parent @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_parent = null;
    /**
     * @return class;
     */

    public function get_parent() { return $this->_parent; }

    public function set_parent($pValue) { $this->_parent = $pValue; }


    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ isValid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pParams
     * @return void
     */
    public function isValid ($pParams) {
        if (array_key_exists('title', $pParams)):
            $this->get_domain()->set_title($pParams['title']);
        endif;
        if (array_key_exists('lead', $pParams)):
            $this->get_domain()->set_lead($pParams['lead']);
        endif;
        if (array_key_exists('content', $pParams)):
            $this->get_domain()->set_content($pParams['content']);
        endif;

        if (array_key_exists('parent', $pParams)):
            $this->set_parent($pParams['parent']);
        endif;

        return parent::isValid($pParams);
    }
}

