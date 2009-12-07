<?php

class Game_Form_Gameresourcetypes 
extends Zupal_Fastform_Domainform {

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 */
    public function _init () {
        $this->_init_gametypes_menu();
        $this->_init_rc_menu();
        $this->set_template('Game_Form_Gameresoucetypestemplate');
    }

    protected function _domain_class() {
        return 'Game_Model_Gameresourcetypes';
    }

    protected function _ini_path() {
        return preg_replace('~php$~', 'ini', __FILE__);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _init_gametypes_menu @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function _init_gametypes_menu () {
        $gametypes = Game_Model_Gametypes::getInstance()->options();
        $this->game_type->set_data_source($gametypes);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _init_gametypes_menu @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function _init_rc_menu () {
        $game_type = $this->game_type->get_value();
        if (!$game_type) return;
        $resource_classes = Game_Model_Gameresourceclasses::getInstance()->options($game_type);
        $this->resource_class->set_data_source($resource_classes);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ resource_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_resource_class = NULL;
    function get_resource_class($pReload = FALSE) {
        if ($pReload || is_null($this->_resource_class)):
        // process
            $this->_resource_class = Game_Model_Gameresourceclasses::getInstance()->get($this->resource_class->get_value());
        endif;
        return $this->_resource_class;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_game_type @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_get_game_type = NULL;
    function get_get_game_type($pReload = FALSE) {
        if ($pReload || is_null($this->_get_game_type)):
        // process
            $this->_get_game_type =  Game_Model_Gametypes::getInstance()->get($this->game_type->get_value());;
        endif;
        return $this->_get_game_type;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ property_label @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pIndex
     * @return <type>
     */
    public function string_label ($pIndex) {
        $label = '';
        if ($rc = $this->get_resource_class()):
            $prop = "string_{$pIndex}_name";
            $label = $rc->$prop;
        endif;

        return $label ? $label : "String $pIndex";
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ value_label @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pindex
     * @return <type>
     */
    public function value_label ($pIndex) {
        $label = '';
        if ($rc = $this->get_resource_class()):
            $prop = "value_{$pIndex}_name";
            $label = $rc->$prop;
        endif;

        return $label ? $label : "Value $pIndex";
    }
}

