<?php

class Zupal_Model_Schema_Field
extends ArrayObject{

    public function __construct($pParams = array()) {
        parent::__construct($pParams);
        
        if (empty($this['type'])){
            $this['type'] = 'string';
        }

        if (empty($this['default'])){
            $this['default'] = NULL;
        }
        
    }

    public function name(){
        return $this['name'];
    }

    public function type(){
        return $this['type'];
    }

    public function is_key(){
        $out = array_key_exists('key', $this) ? $this['key'] : FALSE;
       // echo __METHOD__ . ': checking for ', $this->name(), ' result = ', $out, "\n";
        return $out;
    }

    public function clean_value($pValue) {
        switch($this->type()) {
            case 'int':
            case 'integer':
                return (int)$pValue;
                break;

            case 'str':
            case 'string':
            case 'text':
                return (string)$pValue;
                break;

            case 'array':
                return (array) $pValue;
                break;

            case 'object':
            case 'obj':
            case 'stdClass':
                return (object) $pValue;
                break;
            
            default:
                return $pValue;
        }
    }

    private $_label;
    public function label(){
        if (!$this->_label){
            $this->_label = empty($this['label'])? ucwords(str_replace('_', ' ', $this->name())) : $this['label'];
        }
        return $this->_label;
    }
}