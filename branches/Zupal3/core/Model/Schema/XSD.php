<?php

/**
 * An unordered collection of fields.
 *
 * NOTE: because there is no storage signature defiend by this class,
 * should be useable across a wide variety of storage mechanisms.
 *
 * Because the fields are stored in an arrayObject, can be accessed
 * or iterated as an array.
 */
class Zupal_Model_Schema_XSD
        extends Zupal_Model_Schema_Item
        implements Zupal_Model_Schema_IF {

    public function __construct($pSchema, $pPath = NULL, $pClass_Namer = NULL) {
        if ($pClass_Namer) {
            $this->set_class_namer($pClass_Namer);
        }
        $this->_xsd_to_schema($pSchema, $pPath);
    }

    private $_class_namer;

    public function get_class_namer() {
        return $this->_class_namer;
    }

    public function set_class_namer($_class_namer) {
        $this->_class_namer = $_class_namer;
    }

    protected function _class_name($class) {
        $cn = $this->get_class_namer();
        if (!$cn) {
            return $class;
        } elseif ($cn instanceof
                Closure) {
            return $cn($class);
        } elseif (is_object($cn)) {
            return $cn->class_name($class);
        } elseif (is_string($cn)) {
            return sprintf($cn, $class);
        }
    }

    /**
     * input can be a domDocument, filepath, or XML block. 
     * @param DOMDocument|string $pSchema
     * @param string $pPath 
     */
    protected function _xsd_to_schema($pSchema, $pPath) {

        if (is_string($pSchema)) {
            $schema = new DomDocument();
            if (preg_match('~\.xsd$~', $pSchema)) {
                $schema->load($pSchema);
            } else {
                $schema->loadXML($pSchema);
            }
        } elseif ($pSchema instanceof DOMDocument) {
            $schema = $pSchema;
        } else {
            throw new Exception(__METHOD__ . ': bad input ' . print_r($pSchema, 1));
        }

        $xpath = new DOMXpath($schema);
        $xpath->registerNamespace('xs', 'http://www.w3.org/2001/XMLSchema');
        $xml_string = $schema->saveXML();

        $query = "//*[@name='$pPath']/*/xs:element";

        $nodes = $xpath->query($query, $schema);

        //   error_log(__METHOD__ . ": query = $query, result = " . $nodes->length . ' items: ' . print_r($nodes, 1));

        foreach ($nodes as $field) {
            //    $data = $schema->saveXML($field);
            //    error_log($data);
            //  exit();
            $this->_add_xsd_field($field, $schema->saveXML($field));
        }
    }

    private function _add_xsd_field(DomNode $field, $fxml) {

        $class = $name = $type = NULL;
        $serial = FALSE;
        $required = FALSE;

        $min = 0;
        $max = 1;

        foreach ($field->attributes as $a) {
            $n = $a->name;
            $value = preg_replace('~^xs:~', '', $a->value);
            switch (strtolower($n)) {
                case 'name':
                    $name = $value;
                    break;

                case 'type':
                    $type = $value;
                    break;

                case 'minoccurs':
                    $min = (int) $value;
                    break;

                case 'maxocurrs':
                    $max = ($value == 'unbounded') ? NULL : $value;
                    break;
            }
        }

        if ($min > 0) {
            $required = TRUE;
        }

        if (($max > 1) || is_null($max)) {
            $serial = TRUE;
        }

        switch ($type) {
            case 'dateTime':
                $type = 'date';
                break;

            case 'date':
            case 'string':
            case 'int':
            case 'float':
                break;

            default:
                $class = $this->_class_name($type);
                $type = 'class';
        }

        $item = array(
            'type' => $type,
            'required' => $required,
            'serial' => $serial,
            'name' => $name
        );

        if ($min) {
            $item['min'] = $min;
        };

        if ($max && ($max != 1)) {
            $item['max'] = $max;
        }


        if ($class) {
            $item['class'] = $class;
        }

        $this[$item['name']] = $this->make_field($item);
    }

}