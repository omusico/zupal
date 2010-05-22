<?php

interface Zupal_Model_Schema_IF {

    public function defaults();

    /**
     * determines if the passed array / arrayObject's values are valid.
     * @param array | ArrayObject $pData
     * @return boolean true if valid
     */
    public function validate($pData);
}

