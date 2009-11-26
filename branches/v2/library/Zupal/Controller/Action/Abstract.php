<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Abstract
 *
 * @author bingomanatee
 */
abstract class Zupal_Controller_Action_Abstract {

    public function __construct($pController) {
        $this->set_controller($pController);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ route @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public abstract function execute ();

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ controller @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_controller = null;
    /**
     * @return Zupal_Controller_Abstract
     */

    public function get_controller() { return $this->_controller; }

    public function set_controller($pValue) {
        $this->_controller = $pValue;
        $cname = get_class($pValue);

        if (preg_match('~([\w)Controller$~i', $cname, $m)):
            $prefix = $m[1];
            $pp = split('_', $prefix);
            switch(count($pp)):
                case 0:
                    return;
                break;

                case 1:
                    $this->set_controller_name($p[0]);
                break;

                case 2:
                    $this->set_action_prefix($p[0]);
                    $this->set_controller_name($p[1]);
                break;
            endswitch;
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@ magic referenes to controller @@@@@@@@@@@@@@@@@@ */

    public function __call($name,  $arguments) { call_user_func_array($this->get_controller(), $arguments);  }

    public function __get($name) { return $this->get_controller()->$name;  }

    public function __set($name,  $value) {  $this->get_controller()->$name = $value;   }

}