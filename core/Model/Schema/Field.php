<?php

abstract class Zupal_Model_Schema_Field
        extends ArrayObject
        implements Zupal_Model_Schema_Field_IF {

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

    public function required() {
        return!empty($this['required']);
    }

    public function is_serial() {
        return!empty($this['serial']);
    }

    public function is_key() {
        $out = array_key_exists('key', $this) ? $this['key'] : FALSE;
        // echo __METHOD__ . ': checking for ', $this->name(), ' result = ', $out, "\n";
        return $out;
    }

    public function is_series() {
        return!empty($this['serial']);
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
                if ($this->is_series()) {
                    $out = array();
                    $c = $this['class'];
                    foreach ($pValue as $key => $value) {
                        if ($value instanceof $c) {
                            $out[$key] = $value;
                        }
                    }
                    return $out;
                } else {
                    if (!$pValue instanceof $this['class']) {
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

    public function validate($pData) {

        if (array_key_exists($this->name(), $pData)) {
            $value = $pData[$this->name()];
        } else {
            $value = NULL;
        }

        if ($this->is_serial() && is_array($value)) {

            $errs = array();
            if ($value) {
                if (!is_array($value)) {
                    $value = array($value);
                    // we'll solve it ... with MAGIC!
             //       throw new Exception(__METHOD__ . ": serial field " . $this->name() . " passed non array value " . print_r($value));
                }

                foreach ($value as $i => $v) {
                    $out = $this->validate_value($v, TRUE);
                    if (!($out === TRUE)) {
                        $errs[$i] = $out;
                    }
                }
                if (count($errs)){
                    return $errs;
                } else {
                    return TRUE;
                }
            } elseif ($this->required()) {
                return array('value ' . $this->name() . ': missing and required');
            } else {
                return TRUE;
            }
        } else {
            return $this->validate_value($value);
        }
    }

    public function validate_value($pItem, $pSerial_item = FALSE) {
        throw new Exception(__METHOD__ . ': must be overridden');
    }

    public function toArray(){
        return $this->getArrayCopy();
    }
}