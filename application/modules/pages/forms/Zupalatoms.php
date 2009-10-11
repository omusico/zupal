<?php

class Pages_Form_Zupalatoms extends Zupal_Form_Abstract
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
    }

    public function domain_fields()
    {
        return array("id","atomic_id","version","lead","title","content","created","author","status");
    }

    protected function get_domain_class()
    {
        return "Pages_Model_Zupalatoms";
    }


}

