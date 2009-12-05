<?php

class Game_Form_Gamesessions
extends Zupal_Fastform_Domainform {

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 */
    public function _init () {
        $this->_init_gametypes_menu();
    }

    protected function _domain_class() {
        return 'Game_Model_Gamesessions';
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

}