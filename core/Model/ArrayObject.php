<?php

/**
 * A slightly modified arrayObject that allows
 * class I/O syntax.
 *
 */
class Zupal_Model_ArrayObject
        extends ArrayObject {

    public function __get($name) {
        if ($this->offsetExists($name)) {
            return $this->offsetGet($name);
        } else {
            return NULL;
        }
    }

    public function __set($name, $value) {
        if (
                is_array($value)
                && $this->offsetExists($name)
                && ($this[$name] instanceof ArrayObject)
        ) {
            $this[$name]->exchangeArray($value);
        } else {
            $this->offsetSet($name, $value);
        }
    }

    public function offsetGet($name) {
        if (func_num_args() <= 1) {
            $default = NULL;
            if ($this->offsetExists($name)) {
                return parent::offsetGet($name);
            }
        } else {
            $opts = func_get_args();
            $default = array_pop($opts);
            foreach ($opts as $n) {
                if ($this->offsetExists($n)) {
                    return parent::offsetGet($n);
                }
            }
        }

        return $default;
    }

}