<?php

/**
 * input date format. NOTE: dates are all saved as unix timestamp ints.
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Field_Class extends Zupal_Model_Schema_Field {

    public function validate($pData) {

        $value = empty($pData[$this->name()]) ? NULL : $pData[$this->name()];
        $c = $this->get_class();
        $out = array();

        if (!$value) {
            if ($this->is_required()) {

                $out[] = array(
                    'field' => $this->name(),
                    'value' => $value,
                    'message' => 'absent, and required (must be nonzero)'
                );
            }
        } elseif ($this->is_series()) {
            foreach ($data as $o) {
                if (!($o instanceof $c)) {
                    $out[] = array(
                        'field' => $this->name(),
                        'value' => $value,
                        'message' => 'must be array of ' . $c
                    );
                }
            }
        } elseif (!($value instanceof $c)) {

            $out[] = array(
                'field' => $this->name(),
                'value' => $value,
                'message' => 'must be instance of ' . $c
            );
        }

        return count($out) ? $out : TRUE;
    }

    public function clean_value($pValue) {
        if ($this->is_series()) {
            $out = array();
            $c = $this->get_class();
            foreach ($pValue as $key => $value) {
                if ($value instanceof $c) {
                    $out[$key] = $value;
                }
            }
            return $out;
        } elseif (!$pValue instanceof $this['class']) {
            if ($this->required()) {
                return new $c();
            } else {
                return NULL;
            }
        } else {
            return $pValue;
        }
    }

    /**
     * the default value of a field. can be of any type.
     */
    public function get_default() {
        return FALSE;
    }

    public function get_class() {
        return $this['class'];
    }

}

