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
class Zupal_Model_Schema_Field_Int
extends Zupal_Model_Schema_Field_Number {
    public function __construct($array) {
        parent::__construct($array);
    }

    public function validate_value($value, $pSerial_item = FALSE) {

        $out = parent::validate_value($value, $pSerial_item);
        
        if ($out === TRUE){
            $out = array();
        }

        if ($value && ($value - (int) $value)){
            $out[] = array(
              'field' => $this->name(),
                'value' => $value,
                'message' => 'must be a whole number'
            );
        }

        return count($out) ? $out : TRUE;
    }

    public function clean_value($value){
        return is_numeric($value) ? (int) $value : 0;
    }
}

