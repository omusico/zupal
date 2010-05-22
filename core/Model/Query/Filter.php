<?php

/**
 * Description of Filter
 *
 * @author bingomanatee
 */
class Zupal_Model_Query_Filter {
    public $field;
    public $relation = '=';
    public $value;

    public function __construct($field, $value = NULL, $relation = '=') {
        if (is_array($field)) {
            if (array_key_exists('field', $field)) {
                extract($field);
            } else {
                reset($field);
                $field_values = each($field);
                $field = $field_values['key'];
                $value = $field_values['value'];
            }
        }

        $this->field = $field;
        $this->value = $value;
        $this->relation = $relation;
    }

    public function test(Zupal_Model_Data_IF $data) {
        switch($this->relation) {
            case '=':
                $field = $this->field;
                if ($data[$field] == $this->value) {
                    return TRUE;
                } else {
                    return FALSE;
                }
                break;

            default:
                throw new Exception(__METHOD__ . ": relation {$this->relation} not implemented");

        }
    }

    public function find(Zupal_Model_Container_IF $pContainer, $limit = NULL, $sort = NULL){

        $candidates = array();

        foreach($pContainer->iterator() as $data){
            if ($this->test($data)){
                $candidates[] = $data;
            }
        }
    }
}
