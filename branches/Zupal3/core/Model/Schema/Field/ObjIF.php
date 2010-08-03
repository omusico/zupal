<?php

/**
 * every object embedded as a field has to have a toArray method.
 * @author bingomanatee
 */

interface Zupal_Model_Schema_Field_ObjIF {

    function __construct(Zupal_Model_Data_IF $pData, array $pValue, $pName, $pOptions = NULL);

    function init();
    
    function toArray();
}

