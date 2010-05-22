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
class Zupal_Model_Schema_Field_Array
extends Zupal_Model_Schema_Field {
    public function __construct($array) {
        parent::__construct($array);
    }

    public function validate($data) {
        $value = array_key_exists($this->name(), $data) ?
                $data[$this->name()]:
                NULL;
        
        $out = array();
        if (!is_array($value)){
            $out[] = array(
              'field' => $this->name(),
                'value' => $value,
                'message' => 'must be an array'
            );
        }

        return count($out) ? $out : TRUE;
    }

    public function clean_value($value){
        return (array) $value;
    }
}

