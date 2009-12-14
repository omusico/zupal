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
extends Zupal_Fastform_Tag_Abstract {

    public function __construct($name, $label, $value = NULL, 
        $props = null, $form = null, $pData = NLLL) {
        if (is_array($name)):
            extract($name);
        endif;

        if (is_string($name)):
            $this->set_name($name);

            if (!$label):
                $label = ucwords(str_replace('_', ' ', $name));
            endif;
        endif;

        $this->set_value($value);
        $this->set_label($label);

        parent::__construct($props, '');

        if ($form):
            $this->set_form($form);
        endif;

        if ($pData && is_array($pData)):
            $this->set_data_source($pData);
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ express_value @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function express_value () {
        return '<?= $' . $this->get_name() . ' ?>';
    }

    public function express_props() {
        $this->set_value($this->express_value());
        return $this->render_props();
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

    protected $_props = array();

    public function set_prop( $pID, $pValue) {
        if (!$pID) return;

        switch (strtolower($pID)):

            case 'id':
                return $this->set_id($pValue);
                break;

            case 'value':
                return $this->set_value($pValue);
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

            case 'width':
                return $this->set_width($pValue);
                break;

            case 'required':
                return $this->set_required($pValue);
                break;

            case 'description':
                return $this->set_description($pValue);
                break;

            case 'data':
            case 'mulitoptions':
                return $this->set_data_source($pValue);
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

//        if ($this->get_width()):
//            $out = $this->_add_width($out);
//        endif;
        return $out;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _add_width @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pParams
     * @return array
     */
    public function _add_width (array $pParams) {

        if (array_key_exists('style', $pParams)):
            $pParams['style'] .= "; width: {$this->width()};";
        else:
            $pParams['style'] = "width: {$this->width()};";
        endif;

        return $pParams;
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

    /**
     * @return class;
     */

    public function get_width() {
        return $this->get_style('width');
    }

    public function set_width($pValue) {
        if (is_numeric($pValue)):
            $pValue = ((int) $pValue) . 'px';
        endif;
        $this->set_style('width', $pValue);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ width @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function width () {
        return $this->get_width();
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ required @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_required = FALSE;
    /**
     * @return boolean;
     */

    public function get_required() { return $this->_required; }

    public function set_required($pValue) { $this->_required = $pValue ? TRUE : FALSE; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ description @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_description = null;
    /**
     * @return string;
     */

    public function get_description() { return $this->_description; }

    public function set_description($pValue) { $this->_description = $pValue; }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ description @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function description () {
        return ($this->get_required() ? '<span class="required">Required</span> ' : '')
        . $this->get_description();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ error @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_error = null;
    /**
     * @return string;
     */

    public function get_error() { return $this->_error; }

    public function set_error($pValue) { $this->_error = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ error @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function error () {
        $error = $this->get_error();
        if ($error):
            return sprintf('<span class="error">%s</span>', $error);
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ express @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function express () {
        $this->set_value(  '<?= $' . $this->get_name() . ' ?>');
        return parent::express();
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ hidden @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return boolean
     */
    public function display_props () {
        return array('show_label' => TRUE, 'show_field' => TRUE, 'hidden' => FALSE);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ validate @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function validate () {
        if ($this->get_required() && (!($this->get_value()))):
            $this->set_error('This field is required');
            return FALSE;
        endif;
        return TRUE;
    }
}
