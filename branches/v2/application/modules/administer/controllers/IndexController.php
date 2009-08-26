<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Administer_IndexController extends Zupal_Controller_Abstract
{
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param <type>
 * @return <type>
 */
    public function init () {
        $this->_helper->layout->setLayout('admin');
        parent::init();
    }
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 */
    public function indexAction () {
        $this->view->placeholder( 'page_title' )->set('Administer');
    }
}