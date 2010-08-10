<?php

/**
 * input date format. NOTE: dates are all saved as unix timestamp ints.
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Field_Mongoid extends Zupal_Model_Schema_Field {

    public function validate($pData) {

        $value = empty($pData[$this->name()]) ? NULL : $pData[$this->name()];
        $out = array();

        if (empty($value)) {
            if ($this->required()) {

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
        } elseif (!$value instanceof MongoId) {

            $out[] = array(
                'field' => $this->name(),
                'value' => $value,
                'message' => 'must be instance of MongoId'
            );
        }

        return count($out) ? $out : TRUE;
    }

    public function clean_value($pValue) {
        if ($this->is_series()) {
            $out = array();
            foreach ($pValue as $key => $value) {
                if (!$value instanceof MongoId) {
                    $out[$key] = new MongoId($value);

                } elseif (is_array($value) && array_key_exists('_id', $value)){
                    $out[$key] = new MongoId($value['_id']);
                }
            }
            return $out;
        } elseif (!$pValue instanceof MongoId) {
            if ($this->required()) {
                if (is_array($pValue) && (array_key_exists('_id', $pValue))) {
                    $pValue = $pValue['_id'];
                }
                return new MongoId($pValue);
            } else {
                return NULL;
            }
        } else {
            return $pValue;
        }
    }

    /**
     * not applicable probably
     */
    public function get_default() {
        return NULL;
    }

}

