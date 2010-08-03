<?php

class Zupal_Model_Schema_Field
extends ArrayObject
implements Zupal_Model_Schema_Field_IF
{

    public function __construct($pParams = array()) {
        parent::__construct($pParams);

        if (empty($this['type'])) {
            $this['type'] = 'string';
        }

        if (empty($this['default'])) {
            $this['default'] = NULL;
        }
        
    }

    public function name() {
        return $this['name'];
    }

    public function type() {
        return $this['type'];
    }

    public function required(){
        return !empty($this['required']);
    }

    public function is_serial(){
        return !empty($this['serial']);
    }

    public function is_key() {
        $out = array_key_exists('key', $this) ? $this['key'] : FALSE;
        // echo __METHOD__ . ': checking for ', $this->name(), ' result = ', $out, "\n";
        return $out;
    }

    public function is_series(){
        return !empty($this['serial']);
    }

    public function clean_value($pValue) {
        switch ($this->type()) {
            case 'int':
            case 'integer':
                return (int) $pValue;
                break;

            case 'str':
            case 'string':
            case 'text':
                return (string) $pValue;
                break;

            case 'array':
                return (array) $pValue;
                break;

            case 'object':
            case 'obj':
            case 'stdClass':
                return (object) $pValue;
                break;

            case 'class':
                if ($this->is_series()){
                    $out = array();
                        $c = $this['class'];
                    foreach($pValue as $key => $value){
                        if ($value instanceof $c){
                            $out[$key] = $value;
                        }
                    }
                    return $out;
                } else {
                    if (!$pValue instanceof $this['class']){
                        return new $c();
                    }
                }
                break;

            default:
                return $pValue;
        }
    }

    private $_label;

    public function label() {
        if (!$this->_label) {
            $this->_label = empty($this['label']) ? ucwords(str_replace('_', ' ', $this->name())) : $this['label'];
        }
        return $this->_label;
    }

    /**
     * the default value of a field. can be of any type.
     */
    public function get_default() {
        return $this['default'];
    }

}