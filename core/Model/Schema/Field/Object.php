<?php

/**
 * intended to reflect stdClass items. Probably should deprecate in favor of class operator. 
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Field_Object
        extends Zupal_Model_Schema_Field {

    public function validate_value($value, $pSerial_item = NULL) {

        $out = array();

        if (!(is_object($value) && (is_array($value)))) {

            $out[] = array(
                'field' => $this->name(),
                'value' => $value,
                'message' => 'must be an object, or an array'
            );
        }

        return count($out) ? $out : TRUE;
    }

    public function hydrate_value($pItem, $pIndex = NULL) {
        if (empty($pItem)) {
            $obj = array();
        } elseif (method_exists($pItem, 'toArray')) {
            $obj = $pItem->toArray();
        } else {
            $obj = (array) $pItem;
        }
        return $obj;
    }

    public function value_to_xml($pValue, DomDocument $pDom, DomNode $pRoot) {
        $pValue = $this->hydrate_value($pValue);

        foreach ($pValue as $key => $value) {
            $this->_add_xml_item($pDom, $pRoot, $key, $value);
        }
    }

    public function _add_xml_item(DomDocument $pDom, DomNode $pRoot, $key, $value) {
        if (is_array($value)) {
            $value_root = $pDom->createElement($key);

            foreach ($value as $value_key => $value_value) {
                $this->_add_xml_item($pDom, $value_root, $value_key, $value_value);
            }
        } else {
            $value_root = $pDom->createElement($key, $value);
        }
        $pRoot->appendChild($value_root);
    }

}

