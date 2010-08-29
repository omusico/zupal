<?php

abstract class Zupal_Model_Schema_Field
        extends Zupal_Model_ArrayObject
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

    public function hydrate($value) {
        if ($this->is_serial()) {
            foreach ((array) $value as $i => $v) {
                $value[$i] = $this->hydrate_value($v);
            }
            return $value;
        } else {
            return $this->hydrate_value($value);
        }
    }

    public function hydrate_value($pItem, $pIndex = NULL) {
        throw new Exception(__METHOD__ . ': Must be implemented in descendant');
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

        if (is_null($value)) {
            if ($this->required()) {
                return array('value ' . $this->name() . ': missing and required');
            } else {
                return TRUE;
            }
        } elseif ($this->is_serial()) {
            if (empty($value)){
                return TRUE;
            }
            $errs = array();
            foreach ((array) $value as $i => $v) {
                $out = $this->validate_value($v, $i);
                if (!($out === TRUE)) {
                    $errs[$i] = $out;
                }
            }
            return (count($errs)) ? $errs : TRUE;
        } else {
            return $this->validate_value($value);
        }
    }

    public function validate_value($pItem, $pSerial_item = NULL) {
        throw new Exception(__METHOD__ . ': must be overridden');
    }

    public function toArray() {
        return $this->getArrayCopy();
    }

}