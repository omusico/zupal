<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Administer_ModulesController extends Zupal_Controller_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param <type>
 * @return <type>
 */
    public function init () {
        $this->_helper->layout->setLayout('admin');
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 */
    public function indexAction () {
        $modules_table = new Administer_Model_DbTable_Modules();

        $m = Administer_Model_Modules::getInstance();
        $m->update_from_filesystem();
        $this->view->modules = $modules_table->fetchAll(NULL, 'folder');
        
        
    }
}