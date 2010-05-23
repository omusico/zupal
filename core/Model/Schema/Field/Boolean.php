<?php

/**
 * input date format. NOTE: dates are all saved as unix timestamp ints.
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Field_Boolean
extends Zupal_Model_Schema_Field {

    public function validate($data) {
        // there are no validation requirements for booleans.
        return TRUE;
    }

    public function clean_value($pValue) {
        return $pValue ? TRUE : FALSE;
    }
    
}

