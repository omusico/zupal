<?php

/**
 * input date format. NOTE: dates are all saved as unix timestamp ints.
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Field_Class
        extends Zupal_Model_Schema_Field {

    public function validate_value($value, $pSerial_item = FALSE) {

        $c = $this->get_class();

        $out = array();
        
        if (empty($value)) {
            if ($this->is_required()) {

                $out[] = array(
                    'field' => $this->name(),
                    'value' => $value,
                    'message' => 'absent, and required'
                );
            }
        } elseif (is_object($value)) {
// might accept stdClass ... ?
            if (!$value instanceof $c) {
                $out[] = array(
                    'field' => $this->name(),
                    'value' => $value,
                    'message' => 'must be instance of ' . c
                );
            }
        } elseif (!is_array($value)) {

            $out[] = array(
                'field' => $this->name(),
                'value' => $value,
                'message' => 'must be instance of array, or ' . $c
            );
        }

        return count($out) ? $out : TRUE;
    }

    public function clean_value($pValue) {
        $c = $this->get_class();

        if ($this->is_series()) {
            $out = array();
            foreach ($pValue as $key => $value) {
                if ($value instanceof $c) {
                    $out[$key] = $value;
                }
            }
            return $out;
        } elseif ($pValue instanceof $this['class']) {
            return $pValue;
        } elseif ($this->required()) {
            // risky. 
            return new $c();
        } else {
            return NULL;
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
        $d_value = empty($data[$name]) ? array() : (array) $data[$name];

        /* @var $c_obj Zupal_Model_Schema_Field_ObjIF */
        if ($this->is_serial()) {
            // note in this case we are not sure the series is made of objs or not.
            foreach ($d_value as $i => $d_value_item) {
                if (is_array($d_value_item)) {
                    $c_obj = new $c($data, $d_value_item, $name, $i);
                    $classes[] = $c_obj;
                    $d_value[$i] = $c_obj;
                }
            }
            $data[$name] = $d_value;
        } else {
            $c_obj = new $c($data, $d_value, $name);
            $classes[] = $c_obj;
            $data[$name] = $c_obj;
        }
    }

}

