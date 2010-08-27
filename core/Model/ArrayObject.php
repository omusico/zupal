<?php

/**
 * A slightly modified arrayObject that allows
 * class I/O syntax.
 *
 */

class Zupal_Model_ArrayObject
extends ArrayObject {

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