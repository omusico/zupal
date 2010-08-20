<?php

/**
 * input date format. NOTE: dates are all saved as unix timestamp ints.
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Field_Boolean
extends Zupal_Model_Schema_Field {

    public function validate_value($value, $pSerial_item = FALSE) {
        // there are no validation requirements for booleans.
        return TRUE;
    }

    public function clean_value($pValue) {
        return $pValue ? TRUE : FALSE;
    }


    /**
     * the default value of a field. can be of any type.
     */
    public function get_default() {
        return FALSE;
    }
}

