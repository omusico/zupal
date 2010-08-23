<?php

class Zupal_Model_Data_Hydrator {

    public static function hydrate(array $out, Zupal_Model_Schema_IF $schema = NULL){
        if ($schema) {
            /* @var $field Zupal_Model_Schema_Field_IF */
            foreach ($this->get_schema() as $field) {
                $name = $field->name();
                if (!empty($out[$name])) {
                    $value = $field->clean_value($out[$name]);

                    if ($field->is_serial()) {
                        foreach ($value as $v => $o) {
                            if (is_object($o) && method_exists($o, 'toArray')) {
                                $value[$v] = $o->toArray();
                            }
                        }
                    } else {
                        if (is_object($value)) {
                            $value = $value->toArray();
                        }
                    }

                    $out[$name] = $value;
                }
            }
        }

        foreach ($out as $k => $v) {
            if (is_object($v) && method_exists($v, 'toArray')) {
                $out[$k] = $v->toArray();
            }
        }
        return $out;
    }
}