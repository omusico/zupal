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

    protected $_init_status = NULL;

    public function __construct($pController) {
        $this->set_controller($pController);
        $this->_init_status = $this->init();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * an extension point. 
     */
    public function init () {}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ route @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public abstract function run();

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ respond @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * an (optional handler for (actionname)response
     * - handles form response from base action
     */
    public function response () {  }

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

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ view @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function view ($pProperty, $pValue) {
        $view = $this->get_controller()->view;
        if ($pProperty):
            $view->$pProperty = $pValue;
        endif;
        
        return $view;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@ magic referenes to controller @@@@@@@@@@@@@@@@@@ */

    public function __call($name,  $arguments) { return call_user_func_array(array($this->get_controller(), $name), $arguments);  }

    public function __get($name) { return $this->get_controller()->$name;  }

    public function __set($name,  $value) {  $this->get_controller()->$name = $value;   }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ forward @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $p1, $p2 = NULL, $p3 = NULL, $p4 = NULL
     * @return <type>
     */
    public function forward ($p1, $p2 = NULL, $p3 = NULL, $p4 = NULL) {
        $this->get_controller()->forward($p1, $p2, $p3, $p4);
    }
}