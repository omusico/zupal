<?php

/**
 * input class format. NOTE: dates are all saved as unix timestamp ints.
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Field_Class
        extends Zupal_Model_Schema_Field {

    public function validate_value($pValue, $pSerial_item = NULL) {
        $out = array();

        $c = $this->get_class();

        if (!$pValue) {
            if ($this->required()) {
                $out[] = array('field' => $this->name(),
                    'value' => $pValue,
                    'message' => 'missing, and required');
            } else {
                return TRUE;
            }
        } elseif (is_object($pValue)) {
// might accept stdClass ... ?
            if (!$pValue instanceof $c) {
                $out[] = array(
                    'field' => $this->name(),
                    'value' => $pValue,
                    'message' => 'must be instance of ' . $c
                );
            }
        } elseif (!is_array($pValue)) {
            $out[] = array(
                'field' => $this->name(),
                'value' => $value,
                'message' => 'must be array, or instance of ' . $c
            );
        }


        return count($out) ? $out : TRUE;
    }

    public function hydrate_value($pItem, $pIndex = NULL) {
        if ($pItem instanceof Zupal_Model_Schema_Field_ClassIF) {
            return $pItem->hydrate();
        } elseif (is_array($pItem)) {
            return $pItem;
        } elseif (method_exists($pItem, 'toArray')) {
            return $pItem->toArray();
        } else {
            return NULL;
            //  throw new Exception(__METHOD__ . ': cannot hydrate ' . print_r($pItem, 1));
        }
    }

    /**
     * the default value of a field. can be of any type.
     */
    public function get_default() {
        return FALSE;
    }

    public function get_class() {
        return $this['class'];
    }

    /**
     * note - as a hack, classes are saved out through a parameter
     * for post-installation initialization. 
     * 
     * @param array | ArrayObject $data
     * @param array $classes 
     */
    function post_load(&$data, &$classes) {

        $name = $this->name();
        $c = $this['class'];

        /* @var $c_obj Zupal_Model_Schema_Field_ClassIF */
        $d_value = array_key_exists($name, $data) ? ($data[$name]) : NULL;

        if ($this->is_serial()) {
            // note in this case we are not sure the series is made of objs or not.
           $new_data = array();
            if (!empty($d_value)) {
                foreach ((array) $d_value as $i => $d_value_item) {
                    if (empty($d_value_item)) {
                        continue;
                    }
                    $c_obj = new $c($data, $d_value_item, array('index' => $i, 'name' => $name));
                    $classes[] = $c_obj;
                    $new_data[$i] = $c_obj;
                }
            }
            $data[$name] = $new_data;

        } elseif ($this->required() || (!empty($d_value))) {
            $c_obj = new $c($data, (array) $d_value, array());
            $classes[] = $c_obj;
            $data[$name] = $c_obj;
        }
    }

    public function value_to_xml($item, DomDocument $dom, DomNode $root) {
        error_log(__METHOD__ . ': for ' . get_class($item));
        error_log ($dom->saveXML());

        if (!$item instanceof Zupal_Model_Schema_Field_ClassIF){
            return;
        }
        if ($this->is_serial()){
            $serial_name = $this->offsetGet(Zupal_Model_Schema_Field_Xml::XML_NODE_NAME, $this->name());
         //   $root is the serial node.
            $class_root = $dom->createElement($serial_name);
            $root->appendChild($class_root);
        } else {
            // $root is a named node - just append field values.
            $class_root = $root;
        }

        $item->to_xml($dom, $class_root);
        error_log(__METHOD__ . ': end for ' . get_class($item));
        error_log ($dom->saveXML());
    }
}

