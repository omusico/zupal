<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Options
 *
 * @author bingomanatee
 */
class FastForm_Tag_Options
extends Zupal_Fastform_Tag_Abstract
{

/**
 *
 * @param Zupal_Fastform_Abstract $pForm
 * @param array $pFields
 * @param array $pData
 */
    public function __construct (Zupal_Fastform_Abstract $pForm, $pValue = NULL, $pOptions = NULL) {
        
        $this->set_form($pForm);
        
        if($pFields) $this->load_fields($pFields);
        if ($pProps) $this->load_props($pFields);
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ value @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_value = null;
    /**
     * @return class;
     */

    public function get_value() { return $this->_value; }

    public function set_value($pValue) { $this->_value = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ options @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_options = null;
    /**
     * @return class;
     */

    public function get_options() { return $this->_options; }

    public function set_options($pValue) { $this->_options = $pValue; }


}
