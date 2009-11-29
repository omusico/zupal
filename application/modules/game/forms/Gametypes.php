<?php

class Game_Form_Gametypes extends Zupal_Fastform_Domainform {

    protected function _domain_class() {
        return 'Games_Model_Gametypes';
    }

    protected function _ini_path() {
        return preg_replace('~php$~', 'ini', __FILE__);
    }
    
}
