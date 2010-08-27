<?php

/**
 * input date format. NOTE: dates are all saved as unix timestamp ints.
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Field_Date
        extends Zupal_Model_Schema_Field {

    public function validate_value($value, $pSerial_item = NULL) {
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

    public function hydrate_value($pItem, $pIndex = NULL) {

        if ($pItem instanceof DateTime){
            return $pItem->format('c');
        } elseif (is_numeric($pItem)){
            return date('c', $pItem);
        } else {
            return $pItem;
        }

    }

}

