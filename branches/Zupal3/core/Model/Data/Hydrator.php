<?php

class Zupal_Model_Data_Hydrator {

    public static function hydrate(array $out, Zupal_Model_Schema_IF $schema = NULL) {
        if ($schema) {
            /* @var $field Zupal_Model_Schema_Field_IF */
            foreach ($schema as $field) {
                $name = $field->name();
                if (array_key_exists($name, $out)) {
                    if (array_key_exists('key', $field) && $field['key'] && empty($out[$name])) {
                        unset($out[$name]);
                        continue;
                    }
                    $value = $field->hydrate($out[$name]);

                    $out[$name] = $value;
                }

                if ($field->auto && empty($out[$name])){
                    $out[$name] = $field->auto();
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

    public static function hydrate_mongo_ids(&$pItem, $pKey) {
        if ($pItem instanceof MongoId) {
            $pItem = (string) $pItem;
        }
    }

}