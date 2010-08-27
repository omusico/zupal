<?php

/**
 * input date format. NOTE: dates are all saved as unix timestamp ints.
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Field_Object
        extends Zupal_Model_Schema_Field {

    public function validate_value($value, $pSerial_item = NULL) {

        $out = array();

        if (!(is_object($value) && (is_array($value)))) {

            $out[] = array(
                'field' => $this->name(),
                'value' => $value,
                'message' => 'must be an object, or an array'
            );
        }

        return count($out) ? $out : TRUE;
    }

    public function hydrate_value($pItem, $pIndex = NULL) {
        if (empty($pItem)) {
            $obj = array();
        } elseif (method_exists($pItem, 'toArray')) {
            $obj = $pItem->toArray();
        } else {
            $obj = (array) $pItem;
        }
        return $obj;
    }

}

