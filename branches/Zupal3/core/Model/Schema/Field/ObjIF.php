<?php

/**
 * Represents an object instantiable by the class field. 
 * @author bingomanatee
 */

interface Zupal_Model_Schema_Field_ObjIF {

    function __construct(Zupal_Model_Data_IF $pData, $pValue, $pName, array $pOptions = array());

    function init();
    
}

