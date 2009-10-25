<?php

class Pages_IndexController extends Zupal_Controller_Abstract {

    public function indexAction() {

    }

    public function viewAction() {
        $id = $this->_getParam("id",  NULL );
        $this->view->page = Pages_Model_Zupalpages::getInstance()->get($id);
    //@TODO: add security check
    }

    public function homeAction() {
        $pt = Pages_Model_Zupalpages::getInstance();
        $home_page = $pt->findOne(array('id' => 1));
        $hpaid = $home_page->get_atomic_id();

        $this->view->pages = Model_Zupalbonds::getInstance()->get_bonds_to($hpaid, 'parent', 'atom');
    }


}

