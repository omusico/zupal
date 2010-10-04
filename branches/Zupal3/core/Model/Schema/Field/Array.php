<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Represents a field that is a whole number
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Field_Array
        extends Zupal_Model_Schema_Field
        implements Zupal_Model_Schema_Field_IF {

    public function __construct($array) {
        parent::__construct($array);
    }

    public function validate_value($pItem, $pIndex = NULL) {
        $out = array();
        if (!is_array($pItem)) {
            $out[] = array(
                'field' => $this->name(),
                'value' => $pItem,
                'message' => 'must be an array'
            );
        }

        return count($out) ? $out : TRUE;
    }

    public function hydrate_value($pItem, $pIndex = NULL) {
        return (array) $pItem;
    }

    public function type() {
        
    }

    /**
     * the default value of a field. can be of any type.
     */
    public function get_default() {
        return array();
    }

    public function value_to_xml($item, DomDocument $dom, DomNode $root) {
        foreach ($item as $key => $value) {
            $value = (string) $value;

            if ($this->offsetGet('xml_array_key_in_attr', FALSE)) {
                $item_node = $dom->createElement($this->offsetGet('xml_array_item_name', $this->name() . '_item'), $value);
                $item_node->setAttribute($this->offsetGet('xml_array_key_name', 'key'), $key);
            } else {
                $item_node = $dom->createElement($this->offsetGet('xml_array_item_name', $this->name() . '_item'));
                $item_node->appendChild($dom->createElement($this->offsetGet('xml_array_key_name', 'key'), $key));
                $item_node->appendChild($dom->createElement($this->offsetGet('xml_array_item_name', $this->name() . '_item'), $value));
            }
            $root->appendChild($item_node);
        }
    }

}

