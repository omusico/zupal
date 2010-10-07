<?php

interface Zupal_Model_Util_CallManagerIF {

    public function __construct($target, array $pParams = array());

    /**
     * @return boolean
     */
    public function manages($pMethod_name);

}