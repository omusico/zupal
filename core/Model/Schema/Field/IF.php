<?php

/**
 *
 * @author bingomanatee
 */
interface Zupal_Model_Schema_Field_IF {
    public function name();

    public function type();

    public function required();

    public function is_serial();

    public function clean_value($pItem);

    /**
     * returns TRUE if valid, array of errors if not valid.
     * @return boolean|array
     *
     * @var $pData array|ArrayObject an array keyed to this name.
     *
     */
    public function validate($pData);
    
    /**
     * validates the value of a field; otherwise like validate. 
     */
    public function validate_value($pItem, $pSerial_item = FALSE);
}

