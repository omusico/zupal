<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of XMLdigester
 *
 * @author bingomanatee
 */
class Zupal_Model_Data_XMLdigester {

    public static function digest($xml, Zupal_Model_Schema_IF $schema = NULL) {
        $out = array();
        if ($xml instanceof DOMNodeList){
            $xml = $xml->item(0);
        }
        foreach ($xml->childNodes as $node) {
            if (!($node->nodeType == XML_ELEMENT_NODE)) {
                continue;
            }
            $name = $node->localName;
            /* @var $field Zupal_Model_Schema_Field_IF */
            if ($schema && ($field = $schema->get_field($name))) {
                if ($field->type() == 'class') {
                    $value = $node;
                } else {
                    $value = self::_digest_raw($node);
                }
                if ($field->is_serial()) {
                    if (!array_key_exists($name, $out)) {
                        $out[$name] = array($value);
                    } else {
                        $out[$name][] = $value;
                    }
                } else {
                    $out[$name] = $value;
                }
            } else {
                $value = self::_digest_raw($xml);
                $out[$name] = $value;
            }
        }

        return $out;
    }

    protected static function _digest_raw($xml) {
        $out = array();
        $text = array();
        foreach ($xml->childNodes as $node) {
            // note - ignoring attrs for now.
            switch ($node->nodeType) {
                case XML_ELEMENT_NODE:
                    if (array_key_exists($node->localName, $out)) {
                        $out[$node->localName] = self::_digest_raw($node);
                    } else {
                        $out[$node->localName] = $node->textContent;
                    }
                    break;

                case XML_TEXT_NODE:
                    if (!trim($node->data, " \n\t\r")){
                        continue;
                    }
                    $text[] = $node->data;
                    break;
            }
        }

        if (!count($out)) {
            switch (count($text)) {
                case 0:
                    return NULL;
                    break;

                case 1:
                    return $text[0];
                    break;

                default:
                    return $text;
                    break;
            }
        } else {
            switch (count($text)) {
                case 0:
                    break;

                case 1:
                    $out['__text'] = $text[0];
                    break;

                default:
                    $out['__text'] = $text;
                    break;
            }
        }

        return $out;
    }

}
