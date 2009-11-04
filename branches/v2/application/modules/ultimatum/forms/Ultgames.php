<?php

class Ultimatum_Form_Ultgames extends Zupal_Form_Abstract
{

    public function __construct($pDomain)
    {
        $ini_path = preg_replace('~php$~', 'ini', __FILE__);
        	$config = new Zend_Config_Ini($ini_path, 'fields');
        	parent::__construct($config);
        
        	if ($pDomain):
        	    $this->set_domain($pDomain);
        	    $this->domain_to_fields();
        	endif;
    }

    public function domain_fields()
    {
        return array("id","title","started_on","status");
    }

    protected function get_domain_class()
    {
        return "Ultimatum_Model_Ultgames";
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ add_player @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param int | Model_Users $pUser
     * @return 
     */
    public function add_player ($pUser) {
        return Ultimatum_Model_Ultplayers::for_user_game($pUser, $this);
    }
}

