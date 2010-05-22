<?php

/**
 *
 * @author bingomanatee
 */
interface Zupal_Model_Data_IF
{

    /* @@@@@@@@@@@@@@@@@@@@@@@@@ STATUS @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    const STATUS_NEW        = 'new';
    const STATUS_SAVED     = 'saved';
    const STATUS_UPDATED    = 'updated';
    const STATUS_DELETED    = 'deleted';
    const STATUS_UNKNOWN    = 'unknown';

/**
 *
 * @param string $pSet
 * @return string
 */
    public function status($pSet);

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@ KEY @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * Returns the value of the key field.
     * if $pThrow, throws an exception if absent.
     *
     * @param boolean $pThrow
     * @return scalar
     */
    public function key($pThrow = TRUE);

    /**
     * Some data types auto-key themselves. For those that do not
     * use this method to set key manually
     * @param scalar $pValue
     */
    public function set_key($pValue);

    /**
     * initializes a database removal /filesystem erase of this record.
     * The object may still exist but status should be set to deleted. 
     */
    public function delete();

    public function save();

    /**
     * @return array
     */
    public function toArray();

}

