<?php

class Zupal_View_Page
extends Zend_Layout {

    public function __construct($options = null) {
        parent::__construct($options, FALSE);
    }


    /* @@@@@@@@@@@@@@@@@ INSTANCE @@@@@@@@@@@@@@@@@@@@@@ */

    private static $_instance;

    /**
     * @return Zupal_View_Page
     */
    public static function instance($path = NULL) {
        if (!self::$_instance) {
            if (is_null($path)){
                $path = array('layoutPath' => dirname(__FILE__) . D . 'templates', 'layout' => 'default');
            }
            self::$_instance = new self($path);
        }
        return self::$_instance;
    }


}