<?php

/**
 * input date format. NOTE: dates are all saved as unix timestamp ints.
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Field_Object extends Zupal_Model_Schema_Field {

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
        if ($this->is_series()) {
            $out = array();
            foreach ($pValue as $key => $value) {
                if ($value instanceof stdClass) {
                    $out[$key] = $value;
                } elseif (is_array($value)){
                    $out[$key] = (object) $value;
                }
            }
            return $out;
        } elseif (!$pValue instanceof stdClass) {
            if ($this->required()) {
                return new stdClass();
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

}

