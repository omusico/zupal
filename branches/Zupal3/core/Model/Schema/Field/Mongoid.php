<?php

/**
 * input date format. NOTE: dates are all saved as unix timestamp ints.
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Field_Mongoid
        extends Zupal_Model_Schema_Field {

    public function validate_value($value, $pSerial_item = NULL) {

        $out = array();

        if (empty($value)) {
            if ($this->required()) {

                $out[] = array(
                    'field' => $this->name(),
                    'value' => $value,
                    'message' => 'absent, and required'
                );
            }
        } elseif (!$value instanceof MongoId) {

            $out[] = array(
                'field' => $this->name(),
                'value' => $value,
                'message' => 'must be instance of MongoId'
            );
        }

        return count($out) ? $out : TRUE;
    }

    public function hydrate_value($pValue, $pIndex = NULL) {
        if (empty($pValue)) {
            $pValue = NULL;
        }
        if (!$pValue instanceof MongoId) {
            return new MongoId($pValue);
        } else {
            return $pValue;
        }
    }

    /**
     * not applicable probably
     */
    public function get_default() {
        if (!strcasecmp($this->default, 'now')){
            return new MongoId();
        } else {
        return NULL;            
        }
    }

    /**
     * note - as a hack, classes are saved out through a parameter
     * for post-installation initialization.
     *
     * @param array | ArrayObject $data
     * @param array $classes
     */
    function post_load(&$data, &$classes) {
        $val =  $data[$this->name()];
        if ($val){ 
            if (is_string($val)){
             $data[$this->name()] = new MongoId($val);
            }
        } else {
            if ($this->auto) {
            $data[$this->name()] = new MongoId();
            }
        }
    }

}

