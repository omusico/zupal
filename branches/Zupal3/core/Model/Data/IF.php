<?php

/**
 *
 * @author bingomanatee
 */
interface Zupal_Model_Data_IF {
    /* @@@@@@@@@@@@@@@@@@@@@@@@@ STATUS @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    const STATUS_NEW = 'new';
    const STATUS_SAVED = 'saved';
    const STATUS_UPDATED = 'updated';
    const STATUS_DELETED = 'deleted';
    const STATUS_UNKNOWN = 'unknown';

    /**
     *
     * @param string $pSet
     * @return string
     */
    function status($pSet);

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@ KEY @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * Returns the value of the key field.
     * if $pThrow, throws an exception if absent.
     *
     * @param boolean $pThrow
     * @return variant
     */
    function key($pThrow = TRUE);

    /**
     * Some data types auto-key themselves. For those that do not
     * use this method to set key manually
     * @param scalar $pValue
     */
    function set_key($pValue);

    /**
     * initializes a database removal /filesystem erase of this record.
     * The object may still exist but status should be set to deleted. 
     */
    function delete();

    /**
     * persists the object into the container.
     * Internally branches between insert and update based on existence of key
     */
    function save();

    /**
     * Sometimes its necessary to force the insertion of new data, as with 
     * manually cretaed keys. 
     */
    function insert();

    /**
     * @return array
     */
    function toArray();
}

