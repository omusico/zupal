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
class Zupal_Model_Schema_Item
        extends Zupal_Model_ArrayObject
        implements Zupal_Model_Schema_IF {

    public function __construct($pFields = array()) {
        $pFields = (array) $pFields;
        if (array_key_exists('fields', $pFields)) {
            $fields = $pFields['fields'];
        } else {
            $fields = $pFields;
        }
        $my_fields = array();
        foreach ($fields as $name => $field) {
            if (is_array($field)) {
                if (!is_numeric($name) && !array_key_exists('name', $field)) {
                    $field['name'] = $name;
                }
                $my_fields[$field['name']] = $field;
            }
        }
        $my_fields = array_map(array('Zupal_Model_Schema_Item', 'make_field'), $my_fields);

        parent::__construct($my_fields);
    }

    /**
     * deprecated. 
     * @param <type> $item
     * @return <type>
     */
    public function get_field_object(&$item) {
        if (is_array($item)) {
            $item = self::make_field($item);
        }
        return $item;
    }

    public function get_field($pname) {
        if (!$this->offsetExists($pname)) {
            return NULL;
        }
        return $this->offsetGet($pname);
    }

    public function set_field($pName, Zupal_Model_Schema_Field_IF $pField) {
        $this->offsetSet($pName, $pField);
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

            case 'mongodate':
                $item['type'] = 'mongodate';
                return new Zupal_Model_Schema_Field_Mongodate($item);
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

            case 'mongoid':
                return new Zupal_Model_Schema_Field_Mongoid($item);
                break;

            case 'boolean':
                return new Zupal_Model_Schema_Field_Boolean($item);
                break;
            
            case 'str':
            case 'txt':
            case 'text':
            case 'string':
                $item['type'] = 'string';
                return new Zupal_Model_Schema_Field_String($item);
                break;
            case 'variant':
            default:
                $item['type'] = 'variant';
                return new Zupal_Model_Schema_Field_Variant($item);
        }
    }

    public function hydrate($field, $value) {
        if (isset($this[$field])) {
            return $this[$field]->hydrate($value);
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

        /* @var $field Zupal_Model_Schema_Field_IF */
        foreach ($this as $name => $field) {
            $result = $field->validate($pData);
            if ($result !== TRUE) {
                if (!is_array($result)) {
                    throw new Exception(__METHOD__ . ': field ' . $name . ' returns non-true, non_array ' . print_r($result, 1));
                }
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

    public static function make_from_yml($path) {

        if (!file_exists($path)) {
            throw new Exception(__METHOD__ . ": no file at $path");
        }

        $yml = new \Symfony\Components\Yaml\Yaml();
        $data = $yml->load($path);

        return new self($data);
    }

    public function toArray() {
        $out = array();
        foreach ($this->getArrayCopy() as $name => $field) {
            $out[$name] = $field->toArray();
        }
        return $out;
    }

    public function as_xml($data, DomDocument $dom, $root = NULL){
        if (!$root) {
            $root = $dom->createElement('data');
            $dom->appendChild($root);
        }

        /* @var $field Zupal_Model_Schema_Field_IF */
        foreach($this as $field){
            $node = $field->as_xml($data, $dom);
         //   error_log(__METHOD__ . ': appending ' . $node->nodeName . ' to ' . $root->nodeName);
            $root->appendChild($node);
        }
        return $root;
    }

}