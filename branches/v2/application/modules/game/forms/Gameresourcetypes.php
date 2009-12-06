<?php

class Game_Form_Gameresourcetypes extends Zupal_Form_Abstract
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
        return array("id","game_type","resource_class","atomic_id","cost","score","value_1","value_2","value_3","value_4","value_5","string_1","string_2","string_3","string_4","string_5");
    }

    protected function get_domain_class()
    {
        return "Game_Model_Gameresourcetypes";
    }


}

