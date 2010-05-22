<?php

/**
 * Description of Item
 *
 * @author bingomanatee
 */
class Zupal_Module_Config_Item
extends Zend_Config_Ini {

    public function __construct() {
        $filename = Zupal_Module_Path::instance()->file('config', 'config.ini');
        parent::__construct($filename, $this->zupal_section());
    }

    private $_zupal_section = NULL;

    public function zupal_section() {
        if (is_null($this->_zupal_section)) {
            global $argv, $argc;

            if (isset($_ENV['ZUPAL_SECTION'])) {
                $this->_zupal_section = $_ENV['ZUPAL_SECTION'];
            } elseif (isset($_SERVER['ZUPAL_SECTION'])) {
                $this->_zupal_section = $_SERVER['ZUPAL_SECTION'];
            } elseif (isset(Zupal_Module_Session::instance()->ZUPAL_SECTION)) {
                // this exists mainly to enable unit testing.
                $this->_zupal_section = Zupal_Module_Session::instance()->ZUPAL_SECTION;
            } elseif ($argc > 1) {
                $this->_zupal_section = $argv[1];
            }
        }

        return $this->_zupal_section;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@ INSTANCE BOILERPLATE @@@@@@@@@@@@@@@@@@@@ */

    private static $_instance;

    public static function instance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
    }
}

