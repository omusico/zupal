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
        $this->view->page = new Pages_Model_Zupalpages(1);

        $this->view->pages = Model_Zupalbonds::getInstance()->get_bonds_to($this->view->page->get_atomic_id(), 'parent', 'atom');
    }


}

