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

    /**
     * returns an array-storable value.
     */
    public function hydrate($pItem);

    /**
     * hydrates a single value for a serial field.
     */
    public function hydrate_value($pItem, $pIndex = NULL);

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
    public function validate_value($pItem, $pIndex = NULL);

    /**
     * Adds field data from $pData to the passed root
     */
    public function value_to_xml($pValue, DomDocument $pDom, DomNode $pRoot);

}

