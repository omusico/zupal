<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Represents a field that is a whole number
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Field_Float
extends Zupal_Model_Schema_Field_Number {
    public function __construct($array) {
        parent::__construct($array);
    }

    public function validate_value($value, $pSerial_item = NULL) {

        $out = parent::validate_value($value, $pSerial_item);

        return count($out) ? $out : TRUE;
    }

    public function hydrate_value($pItem, $pIndex = NULL) {
        return is_numeric($pItem) ? (float) $pItem : 0;
    }

}

