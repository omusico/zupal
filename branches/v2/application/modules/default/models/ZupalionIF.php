<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ZupalionIF
 *
 * @author bingomanatee
 */
interface Model_ZupalionIF {

    /**
     * @return Model_ZupalatomIF
     */
    public function from();
    
    /**
     * @return Model_ZupalatomIF
     */
     public function to();

    /**
     * @return Model_ZupalatomIF
     */
     public function bond();


}
