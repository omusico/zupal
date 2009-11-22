<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Domainform
 *
 * @author bingomanatee
 */
abstract class Zupal_Fastform_Domainform
extends Zupal_Fastform_Form {

    public function __construct($pDomain = NULL, $pName = NULL, $pID = NULL,  $pAction = NULL) {
        $this->set_domain($pDomain);
        $config = new Zend_Config_Ini($this->_ini_path(), 'fields');
        $c_array = $this->_filter_config($config->toArray());

        if (!$pAction):
            if (array_key_exists('action', $c_array)):
                $pAction = $c_array['action'];
        endif;
        endif;

        if (!$pName):
            if (array_key_exists('name', $c_array)):
                $pName = $c_array['name'];
            else:
                $pName = get_class($this);
        endif;
        endif;

        if (!$pID):
            $pID = $pName;
        endif;

        $elements = $c_array['elements'];
        foreach($elements as $key => $data):
            if (!array_key_exists('options', $data) || !array_key_exists('label', $data['options'])):
                $elements[$key]['options']['label'] = ucwords(str_replace('_', ' ', $key));
            endif;        
        endforeach;

        parent::__construct($pName, $pID, $pLabel, $pAction, $elements);
        
        if (array_key_exists('controls', $c_array)):
            $this->load_controls($c_array['controls']);
        endif;

        parent::load_field_values($this->get_domain()->toArray());

        $this->_init();
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * An extension point for any customization post-config.
     *
     * @return void
     */
    protected function _init () {
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _filter_configuration @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * An optional extenstion point for any field customization.
     *
     * @param array $pConfig_array
     * @return <type>
     */
    protected function _filter_config (array $pConfig_array) {
        return $pConfig_array;
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ domain @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_domain = null;
    /**
     * @return Zupal_Domain_Abstract;
     */

    public function get_domain() {
        if (!$this->_domain):
            $class = $this->_domain_class();
            $this->_domain = new $class();
        endif;
        return $this->_domain; }

    public function set_domain($pValue) {
        $class = $this->_domain_class();
        if ($pValue):
            if( is_scalar($pValue)):
                $pValue = new $class($pValue);
            elseif (!($pValue instanceof Zupal_Domain_Abstract)):
                throw new exception(__METHOD__ . ' bad value passed: ' . print_r($pValue, 1));
            endif;
        else:
            $pValue = new $class();
        endif;

        $this->_domain = $pValue;

    }

    abstract protected function _domain_class();
    abstract protected function _ini_path(); /*
     * Set to, as a rule:
     * { return preg_replace('~php$~', 'ini', __FILE__); }
     */

    public function __call($name,  $arguments) {
        call_user_func_array(array($this->get_domain(), $name), $arguments);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_field_values @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pFields
     */
    public function load_field_values ($pFields) {
        parent::load_field_values($pFields);

        $this->get_domain()->set_fields($pFields);
    }
}
