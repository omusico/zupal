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

        if (is_object($pValue)) {
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
            throw new Exception(__METHOD__ . ': cannot hydrate ' . print_r($pItem, 1));
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
        $d_value = empty($data[$name]) ? array() : $data[$name];

        /* @var $c_obj Zupal_Model_Schema_Field_ObjIF */
        if ($this->is_serial()) {
            // note in this case we are not sure the series is made of objs or not.
            foreach ($d_value as $i => $d_value_item) {
                $c_obj = new $c($data, $d_value_item, array('index' => $i, 'name' => $name));
                $classes[] = $c_obj;
                $d_value[$i] = $c_obj;
            }
            $data[$name] = $d_value;
        } else {
            $c_obj = new $c($data, $d_value, $name, array());
            $classes[] = $c_obj;
            $data[$name] = $c_obj;
        }
    }

}

