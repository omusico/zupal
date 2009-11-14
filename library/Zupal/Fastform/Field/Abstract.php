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
abstract class Zupal_Fastform_Field_Abstract
extends Zupal_Fastform_Tag_Abstract
{

    public function __construct($pName, $pLabel, $pValue, $pProps = NULL, $pForm = NULL) {
        $this->set_name($pName);
        $this->set_value($pValue);
        $this->set_label($pLabel);
        
        parent::__construct($pProps, '');

        if ($pForm):
            $this->set_form($pForm);
            $this->get_form()->set_field($this);
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ name @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_name = null;
    /**
     * @return class;
     */

    public function get_name() { return $this->_name; }

    public function set_name($pValue) { $this->_name = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ label @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_label = null;
    /**
     * @return class;
     */

    public function get_label() { return $this->_label; }

    public function set_label($pValue) { $this->_label = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ value @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_value = null;
    /**
     * @return class;
     */

    public function get_value() { return $this->_value; }

    public function set_value($pValue) { $this->_value = $pValue; }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ rows @@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @var int
 */
    private $_rows = 1;
    /**
     * used for choice lists or textarea.
     * @return int;
     */

    public function get_rows() { return $this->_rows; }

    public function set_rows($pValue) { $this->_rows = max(1, (int) $pValue); }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ props @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_props = array();

    public function set_prop( $pID, $pValue) {
        if (!$pID) return;

        switch (strtolower($pID)):

            case 'id':
                return $this->set_id($pValue);
                break;

            case 'name':
                return $this->set_name($pValue);
                break;

            case 'label':
                return $this->set_label($pValue);
                break;

            case 'rows':
                return $this->set_rows($pValue);
                break;

            case 'description':
                return $this->set_description($pValue);
                break;

            case 'value':
                return $this->set_value($pValue);
                break;

            case 'data_source':
                return $this->set_data_source($pValue);
            break;

            case 'type':
                return $this->set_type($pValue);
                break;

            default:
                $this->_props[$pID] = $pValue;
        endswitch;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ my_props @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return array
     */
    public function my_props () {
        $out = parent::my_props();

        if ($this->get_name()):
            $out['name'] = $this->get_name();
        endif;
        
        $out['value'] = $this->get_value();

        return $out;
    }
   /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ props @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function props () {
        $out = parent::get_props();
        
        return array_merge($out, $this->my_props());
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ form @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_form = null;
    /**
     * @return Zupal_Fastform_Abstract;
     */

    public function get_form() { return $this->_form; }

    public function set_form(Zupal_Fastform_Abstract $pValue = NULL) { return $this->_form = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ width @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_width = null;
    /**
     * @return class;
     */

    public function get_width() {
        if ($this->_width):
            return $this->_width;
        elseif ($this->get_form() && $this->get_form()->get_width()):
            return $this->get_form()->get_width();
        else:
            return NULL;
    endif;
    }

    public function set_width($pValue) { $this->_width = $pValue; }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ width @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function width () {
        if ($width = $this->get_width()):
            if (is_numeric($width)):
                return $width . 'px';
            else:
                return $width;
        endif;
        else:
            return '';
    endif;
    }

}
