<?php

/**
 * input date format. NOTE: dates are all saved as unix timestamp ints.
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Field_Object
        extends Zupal_Model_Schema_Field {

    public function validate($pData) {

        $value = empty($pData[$this->name()]) ? NULL : $pData[$this->name()];
        $out = array();

        if (empty($value)) {
            if ($this->is_required()) {

                $out[] = array(
                    'field' => $this->name(),
                    'value' => $value,
                    'message' => 'absent, and required'
                );
            }
        } elseif ($this->is_series()) {
            foreach ($data as $o) {
                if (!(is_array($o))) {
                    $out[] = array(
                        'field' => $this->name(),
                        'value' => $value,
                        'message' => 'must be array of arrays'
                    );
                }
            }
        } elseif (!is_array($value)) {

            $out[] = array(
                'field' => $this->name(),
                'value' => $value,
                'message' => 'must be instance of array'
            );
        }

        return count($out) ? $out : TRUE;
    }

    public function clean_value($pValue) {

        if (!empty($pValue)) {
            if ($field->is_serial()) {
                $obj = array();
                if (is_object($pValue)) {
                    $pValue = array($pValue);
                }
                foreach ($pValue as $k => $o) {
                    if (method_exists($o, 'toArray')) {
                        $obj[$k] = $o->toArray();
                    } else {
                        $obj[$k] = (array) $o;
                    }
                }
            } else {
                if (method_exists($pValue, 'toArray')) {
                    $obj = $pValue->toArray();
                } else {
                    $obj = (array) $obj;
                }
            }
        } elseif ($this->is_serial()) {
            $obj = array();
        } else {
            $obj = NULL;
        }
        return $obj;
    }

    /**
     * the default value of a field. can be of any type.
     */
    public function get_default() {
        return FALSE;
    }

}

