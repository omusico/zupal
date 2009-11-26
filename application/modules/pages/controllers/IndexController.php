<?php

class Pages_IndexController extends Zupal_Controller_Abstract {

    public function indexAction() {
        $this->_forward('home');
    }

    public function viewAction() {
        $id = $this->_getParam("id",  NULL );
        $this->view->page = Pages_Model_Zupalpages::getInstance()->get($id);
    //@TODO: add security check
    }

    public function homeAction() {
        $this->view->page = $page = new Pages_Model_Zupalpages(1);
        $aid = $page->get_atomic_id();
        $m = Model_Zupalbonds::getInstance();
        $this->view->pages = $m->get_bonds_to($aid, 'parent', 'from_atom');
    }


/* @@@@@@@@@@@@@ EXTENSION BOILERPLATE @@@@@@@@@@@@@@ */

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controller_dir @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function controller_dir () {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }
    
}

