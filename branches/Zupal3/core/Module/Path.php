<?php

/**
 * Description of Path
 *
 * @author bingomanatee
 */
class Zupal_Module_Path extends ArrayObject {
    //put your code here

    private static $_instance;

    /**
     * @rerurn Zupal_Module_Path
     */
    public static function instance(){
        if (!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     *
     * @return string
     */
    public function file(){
        $path = func_get_args();
        $key = array_shift($path);
        $root = $this[$key];

        return $root . D . join(D, $path);
    }

}

