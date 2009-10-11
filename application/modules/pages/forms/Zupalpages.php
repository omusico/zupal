<?php

class Pages_Form_Zupalpages extends Zupal_Form_Abstract
{

    public function __construct($pDomain)
    {
        $ini_path = preg_replace('~php$~', 'ini', __FILE__);
        	$config = new Zend_Config_Ini($ini_path, 'fields');
        	parent::__construct($config);

                $this->_init_resources_menu();

        	if ($pDomain):
        	    $this->set_domain($pDomain);
        	    $this->domain_to_fields();
                    if ($atom = $pDomain->atom()):
                        $this->title->setValue($atom->title);
                        $this->lead->setValue($atom->lead);
                        $this->content->setValue($atom->content);
                    endif;
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
    public function _init_resources_menu () {
        foreach(Model_Resources::getInstance()->findAll('resource_id') as $resource):
            $this->resource->addMultiOption($resource->identity(), $resource->title);
        endforeach;
    }
}

