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
            $this->domain_to_fields();
        endif;

        $this->load_resources();
        $this->load_modules();
        $this->load_parents();
    }

    public function domain_fields()
    {
        return array("id","name","label","created_by_module","resource","parent","href","callback_class","parameters","if_module","if_controller","sort_by");
    }

    protected function get_domain_class()
    {
        return "Administer_Model_Zupalmenus";
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_parents @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * //@TODO: have an acutal panel abstract
     *
     * @return void
     */
    public function load_parents () {
        $mt = Model_Menu::getInstance();

        $sql = sprintf('SELECT DISTINCT panel FROM `%s` ORDER BY panel;', $mt->table()->tableName());

        $panels = $mt->table()->getAdapter()->fetchCol($sql);

        foreach($panels as $panel):
            $this->parent->addMultiOption($panel, ucwords($panel));
            foreach($this->_parent_menu($panel) as $k => $v):
                $this->parent->addMultiOption($k, $v);
            endforeach;
        endforeach;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _parent_menu @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function _parent_menu ($panel, $parent = NULL, $pDepth = 0) {
        $out = array();

        if ($parent):
            $mt = new Model_Menu($parent);
            $found = $mt->children();
        else:
            $mt = Model_Menu::getInstance();

            $params = array('parent' => 0, 'panel' => $panel);
            $found = $mt->find($params, 'sort_by');
        endif;

        foreach($found as $m):
            $out[$m->identity() . '_' . $panel] = $this->_pm_prefix($pDepth) . $m->label;
            foreach($this->_parent_menu($panel, $m->identity(), $pDepth + 1) as $k => $v):
                $out[$k] = $v;
            endforeach;
        endforeach;

        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _pm_prefix @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param int $pDepth
     * @return string
     */
    
    public function _pm_prefix($pDepth)
    {
        return str_repeat(' |', $pDepth) . ' |_ ';
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_resources @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     *
     * @return void
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

        $this->menumodule->setMultiOptions($options);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain_to_form @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pMenu
     * @return void
     */
    public function domain_to_fields (array $pFields = NULL) {


        parent::domain_to_fields($pFields);
        $this->menumodule->setValue($this->get_domain()->module);
        $this->menucontroller->setValue($this->get_domain()->controller);
        $this->menuaction->setValue($this->get_domain()->action);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ fields_to_domain @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function fields_to_domain (array $pFields = NULL) {
        parent::fields_to_domain($pFields);
        $this->domain()->module = $this->menumodule->getValue();
        $this->domain()->controller = $this->menucontroller->getValue();
        $this->domain()->action = $this->menuaction->getValue();
    }
}

