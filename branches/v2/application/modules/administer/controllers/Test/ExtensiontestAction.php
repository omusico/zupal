<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Extensiontest
 *
 * @author bingomanatee
 */
class Administer_Test_ExtensiontestAction
extends Zupal_Controller_Action_Abstract
{
    
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ execute @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
        public function execute () {
            $this->view->foo = "Foo";
        }

}

