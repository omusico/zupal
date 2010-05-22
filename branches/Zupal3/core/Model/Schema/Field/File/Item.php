<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Item
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Feild_File_Item
implements Zupal_Model_Schema_Field_File_IF
{
    public $model;

    private $_props = array();

    public function  __construct(array $pParams = array()) {
        foreach($pParams as $field => $value) {
            switch($field){
                case 'model':
                    $this->model = $value;
                break;

                default:
                    $this->_props[$field] = $value;
            }
        }
    }
}

