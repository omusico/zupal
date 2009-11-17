<?

abstract class Zupal_Fastform_Abstract
extends Zupal_Fastform_Tag_Form {

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ form_tag @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_form_tag = NULL;
    function form_tag($pReload = FALSE) {
        if ($pReload || is_null($this->_form_tag)):
        // process
            $this->_form_tag = new Zupal_Fastform_Tag_Form();
        endif;
        return $this->_form_tag;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_prop @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pKey
     * @return <type>
     */
    public function set_prop ($pKey, $pValue) {

        switch($pKey):
            case 'field_width':
                return $this->set_field_width($pValue);
                break;
            default:
                return parent::set_prop($pKey, $pValue);
        endswitch;
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
        if (array_key_exists($pName, $this->_fields)):
            return $this->_fields[$pName];
        else:
            return NULL;
        endif;
    }

    public function get_fields() { return $this->_fields; }

    public function __get($name) {
        return $this->get_field($name);
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pFields
     */
    public function load_fields (array $pFields) {
       foreach($pFields as $name => $pField):
        if ($pField instanceof Zupal_Fastform_Field_Abstract):
            $this->set_field($pField);
        elseif (is_array($pField)):
            $label = '';
            $value = '';
            $rows = NULL;
            $options =  $pField['options'];
            $multiOptions = array();

            extract($options);

            switch($pField['type']):

                case 'textarea':
                    if (!$rows):
                        $options['rows'] = 5;
                    endif;
                    // continue! 
                case 'text':                        
                        $field = new Zupal_Fastform_Field_Text($name, $label, $value,$options, $form);
                    break;

                case 'select':
                        $options['type'] = Zupal_Fastform_Field_Choice::CHOICE_DROPDOWN;
                        $field = new Zupal_Fastform_Field_Choice($name, $label, $value, $multiOptions, $options, $form);
                    break;

                case 'list':
                    if (!$rows):
                        $options['rows'] = 5;
                    endif;
                    $options['type'] = Zupal_Fastform_Field_Choice::CHOICE_LIST;
                    $field = new Zupal_Fastform_Field_Choice($name, $label, $value, $multiOptions, $options, $form);
                break;

                case 'radio':

                    break;

                case 'checkbox':

                    break;

                case 'button':

                    break;

                case 'multiCheckbox':

                    break;

                default:
                    throw new Exception(__METHOD__ . ': cannot handle element ' . $pField['type']);
                    
            endswitch;

        endif;
       endforeach;
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ express_body @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return string
     */
    public function express_body () {
        return $this->template()->express();
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
            $props =  array('type' => 'submit', 'width' => 0);
            $submit = new Zupal_Fastform_Field_Button('submit', 'Submit',$props);
            return array($submit);
    endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ label @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_label = null;
    /**
     * @return class;
     */

    public function get_label() { return $this->_label; }

    public function set_label($pValue) { $this->_label = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ field_width @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_field_width = null;
    /**
     * @return class;
     */

    public function get_field_width() { return $this->_field_width; }

    public function set_field_width($pValue) { $this->_field_width = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_field_values @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pValues
     * @return void
     */
    public function load_values (array $pValues) {
        foreach($pValues as $field_name => $value):
            if ($field = $this->get_field($field_name)):
                $field->set_value($value);
            endif;
        endforeach;
    }


}