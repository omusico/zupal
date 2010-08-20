<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of String
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Field_String
extends Zupal_Model_Schema_Field {
    public function __construct($array) {
        parent::__construct($array);
    }

    public function validate_value($value, $pSerial_item = FALSE) {

        $out = array();

        if ($value) {
            if ((!empty($this['min'])) && $this['min'] > strlen($value)) {
                $out[] = array(
                        'field' => $this->name(),
                        'value' => $value,
                        'message' => "too short(must be at least {$this['min']} letters long)",
                );
            }

            if ((!empty($this['max'])) && $this['max'] < strlen($value)) {
                $out[] = array(
                        'field' => $this->name(),
                        'value' => $value,
                        'message' => "too long(must be no more than {$this['max']} letters long)",
                );
            }
        } else {
            if (array_key_exists('required', $this) && $this['required']) {
                $out[] = array(
                        'field' => $this->name(),
                        'value' => $value,
                        'message' => 'absent, and required'
                );
            }
        }

        return count($out) ? $out : TRUE;
    }
}

