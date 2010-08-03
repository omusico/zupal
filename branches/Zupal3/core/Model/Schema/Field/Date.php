<?php

/**
 * input date format. NOTE: dates are all saved as unix timestamp ints.
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Field_Date
        extends Zupal_Model_Schema_Field {

    public function validate($data) {
        $value = array_key_exists($this->name(), $data) ?
                $data[$this->name()] :
                NULL;
        $out = array();

        //@TODO: date contextual tests

        if (!count($out)) {
            $out = TRUE;
        }
        return $out;
    }

    /**
     * the default value of a field. can be of any type.
     */
    public function get_default() {
        if (!strcasecmp($this['default'], 'now')) {
            return $_SERVER['REQUEST_TIME'];
        }
        return $this['default'];
    }

}

