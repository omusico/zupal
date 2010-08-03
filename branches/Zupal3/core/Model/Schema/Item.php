<?php

/**
 * An unordered collection of fields.
 *
 * NOTE: because there is no storage signature defiend by this class,
 * should be useable across a wide variety of storage mechanisms.
 *
 * Because the fields are stored in an arrayObject, can be accessed
 * or iterated as an array.
 */
class Zupal_Model_Schema_Item extends ArrayObject implements Zupal_Model_Schema_IF {

    public function __construct($pFields = array()) {
        $pFields = (array) $pFields;

        foreach ($pFields as $name => $field) {
            if (!is_numeric($name) && is_array($field) && !array_key_exists('name', $field)) {
                $pFields[$name]['name'] = $name;
            }
        }
        $pFields = array_map(array($this, 'get_field_object'), $pFields);

        parent::__construct($pFields);
    }

    public function get_field_object(&$item) {
        if (is_array($item)) {
            $item = self::make_field($item);
        }
        return $item;
    }

    public function make_field($item) {
        $type = array_key_exists('type', $item) ? $item['type'] : 'string';

        switch (strtolower($type)) {
            case 'int':
            case 'integer':
            case 'whole':
                $item['type'] = 'int';
                return new Zupal_Model_Schema_Field_Int($item);
                break;

            case 'float':
            case 'number':
            case 'numeric':
            case 'decimal':
                $item['type'] = 'float';
                return new Zupal_Model_Schema_Field_Number($item);
                break;

            case 'date':
            case 'datetime':
                $item['type'] = 'date';
                return new Zupal_Model_Schema_Field_Date($item);
                break;

            case 'array':
                return new Zupal_Model_Schema_Field_Array($item);
                break;

            case 'object':
            case 'obj':
                return new Zupal_Model_Schema_Field_Object($item);
                break;

            case 'class':
                return new Zupal_Model_Schema_Field_Class($item);
                break;

            case 'str':
            case 'txt':
            case 'text':
            case 'string':
            default:
                $item['type'] = 'string';
                return new Zupal_Model_Schema_Field_String($item);
        }
    }

    public function clean_value($field, $value) {
        if (isset($this[$field])) {
            return $this[$field]->clean_value($value);
        }
        return $value;
    }

    /**
     *
     * @var Zupal_Model_Schema_Field
     */
    private $_key_obj;

    public function key_field($pAsObject = FALSE) {
        if (is_null($this->_key_obj)) {
            $this->_key_obj = FALSE;
            foreach ($this as $field) {
                if ($field->is_key()) {
                    $this->_key_obj = $field;
                    break;
                }
            }
        }
        return $pAsObject ? $this->_key_obj : $this->_key_obj->name();
    }

    private $_defaults;
    public function defaults() {
        if (!$this->_defaults) {
            $this->_defaults = array();

            foreach ($this as $field => $def) {
                $this->_defaults[$field] = $def->get_default();
            }
        }
        
        return $this->_defaults;
    }

    public function validate($pData) {
        if (!(is_array($pData) || $pData instanceof ArrayObject)) {
            throw new Exception(__METHOD__ . ': bad input ' . print_r($pData, 1));
        }

        $out = array();

        foreach ($this as $name => $field) {
            $result = $field->validate($pData);
            if ($result !== TRUE) {
                $out += $result;
            }
        }

        return count($out) ? $out : TRUE;
    }

    public static function make_from_json($pPath) {

        if (!file_exists($pPath)) {
            throw new Exception(__METHOD__ . ": no file at $pPath");
        }

        $json = file_get_contents($pPath);
        $data = Zend_Json::decode($json);
        return new self($data);
    }

}