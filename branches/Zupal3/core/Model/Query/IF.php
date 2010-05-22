<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author bingomanatee
 */
interface Zupal_Model_Query_IF {

    /**
     * returns an array of Zupal_Model_IF items. 
     * @param Zupal_Model_Container_IF $container
     * return array;
     */
    public function get_data(Zupal_Model_Container_IF $container = NULL);
}

