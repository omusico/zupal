<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ActionRouter
 *
 * @author bingomanatee
 */
class Zupal_Controller_ActionRouter {

    public function __construct($pController, $pDir, $pPrefix) {
        $this->set_controller($pController);
        $this->set_action_path($pDir);
        $this->set_action_prefix($pPrefix);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ action_prefix @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_action_prefix = null;
    /**
     * @return class;
     */

    public function get_action_prefix() { return $this->_action_prefix; }

    public function set_action_prefix($pValue) { $this->_action_prefix = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ action_path @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_action_path = null;
    /**
     * @return class;
     */

    public function get_action_path() { return $this->_action_path; }

    public function set_action_path($pValue) { $this->_action_path = $pValue; }

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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ controller_path @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_controller_path = null;
    /**
     * @return string;
     */

    public function get_controller_path() { return $this->_controller_path; }

    public function set_controller_path($pValue) { $this->_controller_path = $pValue;}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ controller_name @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_controller_name = null;
    /**
     * @return class;
     */

    public function get_controller_name() { return $this->_controller_name; }

    public function set_controller_name($pValue) { $this->_controller_name = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ root @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return string
     */
    public function root () {
        return dirname($this->get_controller_path());
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@ magic referenes to controller @@@@@@@@@@@@@@@@@@ */

    public function __call($name,  $arguments) { call_user_func_array($this->get_controller(), $arguments);  }

    public function __get($name) { return $this->get_controller()->$name;  }

    public function __set($name,  $value) {  $this->get_controller()->$name = $value;   }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ route @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pAction
     */
    public function route ($pAction) {
        $caname = ucfirst(strtolower($pAction));
        $action_class_path = $this->get_action_path() . '/' . $this->get_controller_name() . '/' . $caname . 'Action.php';
        require_once($action_class_path);

        $action_class = $this->get_action_prefix() . '_' .  $this->get_controller_name() . '_' . $caname . 'Action';
        $action = new $action_class($this);

        $action->execute();
    }
}

