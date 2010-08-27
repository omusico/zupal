<?php

/**
 * Description of String
 *
 * @author bingomanatee
 */
class Zupal_Model_Schema_Field_Variant
        extends Zupal_Model_Schema_Field {

    public function __construct($array) {
        parent::__construct($array);
    }

    public function validate_value($value, $pSerial_item = NULL) {
        return TRUE;
    }

  /**
   * 
   * note - there's no safe way to know how to hydrate a variant -
   * better hope it reads!
   * @param variant $pItem
   * @param scalar $pIndex
   * @return scalar
   */

    public function  hydrate_value($pItem, $pIndex = NULL) {
        return $pItem;
    }

}

