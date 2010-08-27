<?php

/**
 * A slightly modified arrayObject that allows
 * class I/O syntax.
 *
 */

class Zupal_Model_ArrayObject
extends ArrayObject {

    public function offsetSet($index, $newval) {
        $s = $this->get_schema();
        if ($s->offsetExists($index)) {
            /* @var $field Zupal_Model_Schema_Field_IF */
            $field = $s[$index];
            if ($field->is_serial() && !is_array($newval)) {
                $newval = array($newval);
            }
        }
        return parent::offsetSet($index, $newval);
    }

    public function __get($name) {
        if ($this->offsetExists($name)){
            return $this->offsetGet($name);
        } else {
            return NULL;
        }
    }

    public function __set($name, $value) {
        $this->offsetSet($name, $value);
    }

}