<?php

class Zupal_Model_Schema_Field_Serial
extends Zupal_Model_ArrayObject {

    public function  __toString() {
        return join(', ', array_map( array($this, '_toStringItem'), $this->getArrayCopy()));
    }

    public function _toStringItem($i) {
        if (is_scalar($i)) {
            return $i;
        } elseif (is_object($i)) {
            if (method_exists($i, '__toString')) {
                return (string) $i;
            } else {
                return '[object]';
            }
        }
    }

}