<?php

class Game_Form_Gametypes extends Zupal_Fastform_Domainform {

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 */
    public function _init () {
        $this->_init_resources_menu();
        $this->_init_gametypes_menu();
    }

    protected function _domain_class() {
        return 'Game_Model_Gametypes';
    }

    protected function _ini_path() {
        return preg_replace('~php$~', 'ini', __FILE__);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _init_resources_menu @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    protected function _init_resources_menu () {
        $options = Model_Resources::getInstance()->as_list('(none)');
        $this->resource->set_data_source($options);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _init_gametypes_menu @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function _init_gametypes_menu () {
        $tree = Game_Model_Gametypes::getInstance()->tree($this->get_domain(), Game_Model_Gametypes::FLATTEN_WITH_DEPTH);

        foreach($tree as $node):
            $type =  $mode['type'];
            $string = str_repeat('..', $node['depth']) . $type->title;
        endforeach;
        
    }

}
