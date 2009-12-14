<?

abstract class Zupal_Fastform_Abstract
extends Zupal_Fastform_Tag_Form {

    public function __construct($pName = NULL, $pID = NULL,  $pAction = NULL) {
        $this->_load($pName, $pID, $pAction);

        $this->_init();
    }

    protected function _load($pName = NULL, $pID = NULL,  $pAction = NULL) {
        if (file_exists($this->_ini_path())):
            $config = new Zend_Config_Ini($this->_ini_path(), 'fields');
            $c_array = $config->toArray();
            $c_array = $this->_filter_config($c_array);
        else:
            $c_array = array();
        endif;

        if (!$pAction):
            if (array_key_exists('action', $c_array)):
                $pAction = $c_array['action'];
            endif;
        endif;
        $this->set_action($pAction);

        if (!$pName):
            if (array_key_exists('name', $c_array)):
                $pName = $c_array['name'];
            else:
                $pName = get_class($this);
            endif;
        endif;
        $this->set_name($pName);

        if (!$pID):
            $pID = $pName;
        endif;
        $this->set_id($pID);

        $elements = $c_array['elements'];
        foreach($elements as $key => $data):
            if (!array_key_exists('options', $data) || !array_key_exists('label', $data['options'])):
                $elements[$key]['options']['label'] = ucwords(str_replace('_', ' ', $key));
            endif;
        endforeach;
        $this->load_fields($elements);
        unset($c_array['elements']);

        if (array_key_exists('controls', $c_array)):
            $this->load_controls($c_array['controls']);
            unset($c_array['controls']);
        endif;

        foreach($c_array as $key => $value):
            $this->set_prop($key, $value);
        endforeach;
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

            case 'title_width' :
                return $this->set_title_width($pValue);
                break;
            
            default:
                return parent::set_prop($pKey, $pValue);
        endswitch;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ template @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_template = 'Zupal_Fastform_Template_Table';
    /**
     * @return string;
     */
    public function get_template() { return $this->_template; }

    public function set_template($pValue) { $this->_template = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ template @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_templ = NULL;
    function template($pReload = FALSE) {
        if ($pReload || is_null($this->_templ)):
            $class = $this->get_template();
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
        $this->_fields[] = $pValue;
    }

    public function get_field($pName) {
        if (array_key_exists($pName, $this->_fields)):
            return $this->_fields[$pName];
        else:
            foreach($this->get_fields() as $field):
                if (!strcasecmp($pName, $field->get_name())):
                    return $field;
                endif;
            endforeach;
        endif;
        return NULL;
    }

    public function get_fields() { return $this->_fields; }

    public function __get($name) {
        return $this->get_field($name);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_field_values @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pValues
     * @return void
     */
    public function load_field_values ($pValues) {
        foreach($pValues as $field => $value):
            if ($f = $this->get_field($field)):
                    $f->set_value($value);
            endif;
        endforeach;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pFields
     */
    public function load_fields (array $pFields) {
        foreach($pFields as $name => $pField):
            if (is_array($pField)):
                $pField = $this->_array_to_field($pField, $name);
            endif;

            if ($pField instanceof Zupal_Fastform_Field_Abstract):
                $this->set_field($pField);
            endif;
        endforeach;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _array_to_field @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pField_Array
     * @return Zupal_Fastform_Field_Abstract
     */
    public function _array_to_field (array $pField, $name = '') {
        $label = '';
        $value = NULL;
        $rows = NULL;
        $data = NULL;
        $options =  $pField['options'];
        $multiOptions = array();

        extract($options);

        switch($pField['type']): // a little clumsy -- prob. need to flatten constructors or make consistent.

            case 'hidden':
                $field = new Zupal_Fastform_Field_Hidden($name, $label, $value, $options, $this);
                break;

            case 'textarea':
                if (!$rows):
                    $options['rows'] = 5;
            endif;
            // continue!
            case 'text':
                $field = new Zupal_Fastform_Field_Text($name, $label, $value,$options, $this);
                break;

           case 'password':
                $field = new Zupal_Fastform_Field_Text($name, $label, $value,$options, $this);
                $field->set_prop('password', TRUE);
               break;

            case 'select':
                $options['type'] = Zupal_Fastform_Field_Choice::CHOICE_DROPDOWN;
                $field = new Zupal_Fastform_Field_Choice($name, $label, $value, $options, $this, $multiOptions);
                break;

            case 'list':
                if (!$rows):
                    $options['rows'] = 5;
                endif;
                $options['type'] = Zupal_Fastform_Field_Choice::CHOICE_LIST;
                $field = new Zupal_Fastform_Field_Choice($name, $label, $value, $options, $this, $multiOptions);
                break;

            case 'radio':
                $options['type'] = Zupal_Fastform_Field_Choice::CHOICE_RADIO;

                $field = new Zupal_Fastform_Field_Choice($name, $label, $value, $options, $this, $multiOptions);
                break;

            case 'multiCheckbox':
            case 'checkbox':
                $options['type'] = Zupal_Fastform_Field_Choice::CHOICE_CHECKBOX;

                $field = new Zupal_Fastform_Field_Choice($name, $label, $value, $options, $this, $multiOptions);
                break;

            case 'button':
                $field = new Zupal_Fastform_Field_Button($name, $label, $value, $options, $this);
                break;

            case 'submit':
                $field = new Zupal_Fastform_Field_Button($name, $label, $value, $options, $this);
                $field->set_type('submit');
                break;

            default:
                throw new Exception(__METHOD__ . ': cannot handle element ' . $pField['type']);
        endswitch;
        return $field;
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

    public function add_control($pValue, $pName = NULL) {
        if (is_array($pValue)):
            $pValue = $this->_array_to_field($pValue, $pName = NULL);
        endif;
        $pValue->set_form(NULL); // break link to establish individual field width
        $this->_controls[$pValue->get_name()] = $pValue;
    }

    public function get_control($pID) { return $this->_controls[$pID]; }

    public function get_controls() { return $this->_controls; }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_controls @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pControls
     */
    public function load_controls (array $pControls) {
        foreach($pControls as $name => $control):
            if (is_numeric($name)):
                $name = NULL;
            endif;
            $this->add_control($control, $name);
        endforeach;
    }

   /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controls @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function controls () {
        if (count($controls = $this->get_controls())):
            return $controls;
        else:
            $props =  array('type' => 'submit', 'width' => 0);
            $submit = new Zupal_Fastform_Field_Button('submit', 'Submit', 'Submit',$props);
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


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ title_width @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_title_width = NULL;
    /**
     * @return string;
     */

    public function get_title_width() { return $this->_title_width; }

    public function set_title_width($pValue) { $this->_title_width = $pValue; }

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

    abstract protected function _ini_path(); /*
     * Set to, as a rule:
     * { return preg_replace('~php$~', 'ini', __FILE__); }
     */

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
}