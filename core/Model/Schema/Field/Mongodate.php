<?php

class Zupal_Model_Schema_Field_Mongodate
        extends Zupal_Model_Schema_Field_Date {

    public function hydrate_value($pItem, $pIndex = NULL) {
        return $this->_as_mongo_date($pItem);
    }

    public function post_load($data, &$classes) {

        $name = $this->name();

        /* @var $c_obj Zupal_Model_Schema_Field_ClassIF */
        $d_value = array_key_exists($name, $data) ? ($data[$name]) : NULL;

        if ($this->is_serial()) {
            // note in this case we are not sure the series is made of objs or not.
            $data[$name] = array();
            if (!empty($d_value)) {
                foreach ((array) $d_value as $i => $d_value_item) {
                    $c_obj = $this->_as_mongo_date($d_value_item);
                    if ($c_obj) {
                        $data[$name][$i] = $c_obj;
                    }
                }
            }
        } elseif ($this->required() || (!empty($d_value))) {
            $data[$name] = $this->_as_mongo_date($d_value);
        }
    }

    private function _as_mongo_date($d_value_item) {
        if (empty($d_value_item)) {
            $c_obj = NULL;
        } elseif ($d_value_item instanceof MongoDate) {
            $c_obj = $d_value_item;
        } else {
            if (!is_numeric($d_value_item)) {
                $d_value_item = strtotime($d_value_item);
            }
            $c_obj = new MongoDate($d_value_item);
        }
        return $c_obj;
    }

    public static function to_zend_date(MongoDate $d){
        $out = new Zend_Date($d->secs);
        return $out;
    }
    
}