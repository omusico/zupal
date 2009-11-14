<?

abstract class Zupal_Fastform_Abstract
extends Zupal_Fastform_Tag_Form {

    public function __construct($pName = '', $pID = NULL, $pLabel = '', $pAction = '', $pFields = array(), $pProps = array()) {
        $this->set_name($pName);
        $this->set_label($pLabel);
            $this->set_id($pID);

        if ($pForm_props && count($pForm_props)):
            $this->load_props($pProps);
        endif;

        if ($pAction):
            $this->set_action($pAction);
        endif;

        if ($pFields && count($pFields)):
            $this->load_fields($pFields);
    endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ form_tag @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_form_tag = NULL;
    function form_tag($pReload = FALSE) {
        if ($pReload || is_null($this->_form_tag)):
        // process
            $this->_form_tag = new Zupal_Fastform_Tag_Form();
        endif;
        return $this->_form_tag;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ template @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_template = 'Table';
    /**
     * @return string;
     */
    public function get_template() { return $this->_template; }

    public function set_template($pValue) { $this->_template = ucfirst(strotlower($pValue)); }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ template @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_templ = NULL;
    function template($pReload = FALSE) {
        if ($pReload || is_null($this->_templ)):
            $class = 'Zupal_Fastform_Template_' . $this->get_template();
            // process
            $this->_templ = new $class($this);
        endif;
        return $this->_templ;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_fields = array();
    /**
     *
     * @param Zupal_Fastform_Field_Abstract $pValue
     */
    public function set_field( $pValue) {
        $this->_fields[$pValue->get_name()] = $pValue;
    }

    public function get_field($pName) {
        return $this->_fields[$pName];
    }

    public function get_fields() { return $this->_fields; }

    public function render_field($pField, $pRow_Template = NULL) {
        if (is_string($pField)):
            $pField = $this->get_field($pField);
        endif;

        if (!($pField instanceof Zupal_Fastform_Tag_Abstract)):
            return;
    endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pFields
     */
    public function load_fields (array $pFields) {
        $this->_fields = array_merge($this->_fields, $pFields);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ datas @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_datas = array();

    public function set_data($pID, array $pValue) {
        $this->_datas[$pID] = $pValue;
    }

    public function get_data($pID) { return $this->_datas[$pID]; }

    public function get_datas() { return $this->_datas; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_data @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pData_array
     */
    public function load_data ($pData_array) {
        return $this->datas = array_merge($this->_datas, $pData_array);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_body @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return
     */
    public function get_body () {
        return $this->template()->render();
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controls @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * Controlls
     */
    private $_controls = array();

    public function set_control($pValue, $pID = NULL) {
        if (is_null($pID)):
            array_push($this->_controls, $pValue);
        else:
            $this->_controls[$pID] = $pValue;
    endif;
    }

    public function get_control($pID) { return $this->_controls[$pID]; }

    public function get_controls() { return $this->_controls; }

   /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controls @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function controls () {
        if (count($controls = $this->get_controls())):
            return $controls;
        else:
            $props =  array('type' => 'submit');
            return array(
            new Zupal_Fastform_Field_Button('submit', 'Submit',$props, $this)
            );
    endif;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ label @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_label = null;
    /**
     * @return class;
     */

    public function get_label() { return $this->_label; }

    public function set_label($pValue) { $this->_label = $pValue; }


}