<?php

/**
 * An interface forced on any class that can be stored in field value.
 * Note that the $pData object is the root ancestor of this class - not necessarily the direct parent
 * which may be passed in as a $pOptions value.
 *
 * @author bingomanatee
 */

interface Zupal_Model_Schema_Field_ClassIF {

    /**
     * @var Zupal_Model_Data_IF $pData the root (saveable) data object
     * @var array|DomDocument $pValue The data initialization
     * @var array $pOptions any metadata; some useful values include:
     *  name (of the field in which this object is being saved)
     *  parent (direct ancestor - may or may not == $pData)
     *  index (if a serial class)
     */
     function __construct(Zupal_Model_Data_IF $pData, $pValue, array $pOptions = array());

    /**
     * Called to object after it has been placed and loaded into context. 
     */
    function init();

    /**
     * the primitive (array) version of this object.
     * This is essentially, toArray() - however it conflicts with the data definition. 
     */
    function hydrate();
    
}

