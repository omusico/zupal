<?php

class Zupal_Model_Schema_Field_Xml {
    const XML_NODE_NAME = 'xml_node_name';
    const XML_SERIAL_NODE_NAME = 'xml_serial_node_name';

    /**
     *
     * @param DomDocument $pDom
     * @param Zupal_Model_Data_IF $pData
     * @param Zupal_Model_Schema_Field_IF $pField
     * @param array $pProps
     * @return DomNode
     */
    public static function field_to_node(
    Zupal_Model_Data_IF $pData, DomDocument $pDom, Zupal_Model_Schema_Field_IF
    $pField, array $pProps = array()) {


        if ($pField->is_serial()) {
            $serial_node = self::serial_field_node($pData, $pDom, $pField);
            foreach ($pData[$pField->name()] as $field_value) {
                $pField->value_to_xml($field_value, $pDom, $serial_node);
            }
            return $serial_node;
        } else {
            $node_name = $pField->offsetGet(self::XML_NODE_NAME, 'name', NULL);
            $node = $pDom->createElement($node_name);
            $pField->value_to_xml($pData[$pField->name()], $pDom, $node);
            return $node;
        }
    }

    public static function array_to_node(array $pData, DomDocument $pDom, DomNode $pRoot = NULL) {
        if (!$pRoot) {
            $pRoot = $pDom->createElement('array');
        }

        foreach ($pData as $key => $value) {
            if (is_array($value)) {
                $item_node = $pDom->createElement($key);
                $pRoot->appendChild($item_node);
                self::array_to_node($value, $pDom, $item_node);
            } elseif (is_object($value)) {
                if ($value instanceof Zupal_Model_Schema_Field_ClassIF) {
                    $item_node = $pDom->createElement($key);
                    $value->to_xml($pDom, $item_node);
                } else {
                    $item_node = $pDom->createElement($key, (string) $value);
                }
            } else {
                $pRoot->appendChild($pDom->createElement($key, $value));
            }
        }

        return $pRoot;
    }

    /**
     *
     * @param DomDocument $pDom
     * @param Zupal_Model_Data_IF $pData
     * @param Zupal_Model_Schema_Field_IF $pField
     * @return DomNode
     */
    public static function serial_field_node(
    Zupal_Model_Data_IF $pData, DomDocument $pDom, Zupal_Model_Schema_Field_IF $pField) {

        $serial_node_name = $pField->offsetGet(self::XML_SERIAL_NODE_NAME, $pField->name() . 's');

        $serial_node = $pDom->createElement($serial_node_name);

        return $serial_node;
    }

}