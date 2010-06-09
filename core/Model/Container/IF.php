<?php

/**
 *
 * @author bingomanatee
 */
interface Zupal_Model_Container_IF {

    /**
     * returns a single item via its key. unlike find, accepts only scalar data.
     * @return Zupal_Model_Data_IF
     */
    public function get($pKey);
    
    /**
     * return a blank record;
     * @return Zupal_Model_Data_IF
     */
    public function new_data($pData);

    /**
     * Adds data to the container. Unlike new_data which just
     * creates an object that COULD be saved,
     *  this method actually saves the data.
     * @param Zupal_Model_Data_IF $pData
     */
    public function add($pData);

   /**
    * returns a series of Zupal_Model_Data_IF
    * @param <type> $pQuery
    * @param <type> $limit
    * @param <type> $sort
    * @return array
    */
    function find($pQuery, $limit = NULL, $sort = NULL);

    /**
     * returns a single record
     * @param <type> $pQuery
     * @param <type> $sort 
     * @return Zupal_Model_Data_IF
     */
    function find_one($pQuery, $sort = NULL);

    /**
     * can be a scalar key to get, a query to find or an actual data item.
     * @param variant $pWhat
     * @return boolean
     */
    function has($pWhat);

    /**
     * can be a scalar key to get, a query to find or an actual data item.
     * @param variant $pWhat
     * @return boolean
     * returns true if any actual data was deleted.
     */
    function find_and_delete($pWhat);

    /**
     * deletes a single record
     */
    function delete_data(Zupal_Model_Data_IF $pData);

    /**
     * saves a single record to the database. For SQL, subsumes INSERT/UPDATE.
     * @param Zupal_Model_Data_IF $pData
     */
    function save_data(Zupal_Model_Data_IF $pData);

    /**
     * @return Zupal_Model_Schema_IF
     */
    public function schema();
}
