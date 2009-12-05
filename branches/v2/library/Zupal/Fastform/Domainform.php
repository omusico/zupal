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

        parent::_load($pName, $pID, $pLabel, $pAction, $pFields, $pProps);

        if ($this->get_domain()->isSaved()):
            $data = $this->get_domain()->toArray();
            parent::load_field_values($data); // loads defaults or record field data.
        endif;
        $this->_init();
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

    public function __call($name,  $arguments) {
        call_user_func_array(array($this->get_domain(), $name), $arguments);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_field_values @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * Note -- the construtors load_field_values call is an initializing action --
     * domain to form.
     * this is an update action -- data to domain and form.
     * Overload this method to re-map fields whose name is divergent
     * between the form and the domain.
     * 
     * @param array $pFields
     */
    public function load_field_values ($pFields) {
        parent::load_field_values($pFields);

        $this->get_domain()->set_fields($pFields);
    }
    
    abstract protected function _domain_class();

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * An extension point for customization. 
     */
    protected function _init () {
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function save () {
        $this->get_domain()->save();
    }
}
