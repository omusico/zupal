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
class Zupal_Model_Schema_Field_Number extends Zupal_Model_Schema_Field {

    public function __construct($array) {
        parent::__construct($array);
    }

    public function validate_value($value, $pSerial_item = NULL) {

        $out = array();

        if (is_null($value)) {
            if ($this->required()) {
                $out[] = array(
                    'field' => $this->name(),
                    'value' => $value,
                    'message' => 'absent, and required (must be nonzero)'
                );
            }
        } else {

            if (($this->offsetExists('min')) && $this['min'] > $value) {
                $out[] = array(
                    'field' => $this->name(),
                    'value' => $value,
                    'message' => "too short(must be at least {$this['min']})",
                );
            }

            if (($this->offsetExists('max')) && $this['max'] < $value) {
                $out[] = array(
                    'field' => $this->name(),
                    'value' => $value,
                    'message' => "too long(must be no more than {$this['max']})",
                );
            }
        }

        return count($out) ? $out : TRUE;
    }

    public function  hydrate_value($pItem, $pIndex = NULL) {
        return is_numeric($pItem) ? $pItem : 0;
    }
    /**
     * the default value of a field. can be of any type.
     */
    public function get_default() {
        return 0;
    }

}

