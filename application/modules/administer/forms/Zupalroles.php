<?php

class Administer_Form_Zupalroles extends Zupal_Form_Abstract
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

                $this->load_modules();

                $this->load_resources();
    }

    public function domain_fields()
    {
        return array("role_id","title","notes","rank");
    }

    protected function get_domain_class()
    {
        return "Model_Roles";
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_resources @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function load_modules () {
        $options = array(0 => '(none)', 'zupal' => 'Zupal Core');

        foreach(Administer_Model_Modules::getInstance()->find_all() as $res):
            $options[$res->folder] = $res->title();
        endforeach;

        $this->rolemodule->setMultiOptions($options);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain_to_form @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $prole
     * @return void
     */
    public function domain_to_fields (array $pFields = NULL) {
        parent::domain_to_fields($pFields);
        error_log(__METHOD__ . ': domain = ' . print_r($this->get_domain()->toArray(), 1));
        $module = $this->get_domain()->module;
        error_log(__METHOD__ . ': module = ' . $module);

        $this->rolemodule->setValue($module);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ fields_to_domain @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function fields_to_domain (array $pFields = NULL) {
        parent::fields_to_domain($pFields);
        $this->get_domain()->module = $this->rolemodule->getValue();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_resources @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return <type>
     */

    private static $res_options = array('' => '(choose)', 'Y' => 'Allow', 'N' => 'Deny', '?' => '--');

    public function load_resources () {
        $res_selects = array();

        foreach(Model_Resources::getInstance()->find_all(Model_Resources::getInstance()->table()->idField()) as $res):
            error_log(__METHOD__ . ': loading resource ' . $res->identity());
            
            $spec = array('label' => $res->title);
            $res_name = 'resource_' . $res->identity();
            $res_selects[] = $res_name;
            $spec['name'] = $res_name;
            $spec['multiOptions'] = self::$res_options;

            $select = new Zend_Form_Element_Select($spec);

            $domain = $this->get_domain();
            if ($domain && $domain->isSaved()):
                if ($value = Model_Acl::find_acl($res, $domain)):
                    error_log(__METHOD__ . ': value = ' . $value);
                    $select->setValue($value);
                else:
                    error_log(__METHOD__ . ': no value for res = ' . $res->identity() . ', role = ' . $domain->identity());
                endif;
            else:
                error_log(__METHOD__ . ': domain not saved');
            endif; 
            $this->addElement($select);
        endforeach;
    }
}

