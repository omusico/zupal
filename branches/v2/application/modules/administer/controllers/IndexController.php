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
 * @return <type>
 */
    public function preDispatch () {
        $u = Model_Users::current_user();

        if (!$u || ! $u->can('site_admin')):
            $param = array('error' => 'This area is reserved for administrators');
            $this->_forward('insecure', 'error', 'administer', $param);
        endif;
    }


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