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

}

