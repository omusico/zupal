<?php
/**
 * Session storage for Zupal
 *
 * @author bingomanatee
 */
class Zupal_Module_Session
extends Zend_Session_Namespace {

    private static $_instance = NULL;
    public static function instance(){
        if (is_null(self::$_instance)){
            self::$_instance = new self('zupal');
        }

        return self::$_instance;
    }

}