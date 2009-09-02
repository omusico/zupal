<?php
class Administer_UsersController extends Zupal_Controller_Abstract {
    public function init() {
        $this->_helper->layout->setLayout('admin');

        parent::init();
    }
    /**
     *
     */
    public function indexAction() {
        $uf = new Administer_Form_Userfind();

        $this->view->find_user_form = $uf;

        $this->view->register_user_form = new Form_Userregister();
    }

    public function rolesAction() {
    }

    public function rolesstoreAction()
    {
        $this->_helper->layout->disableLayout();
        $mr = Model_Roles::getInstance();
        $this->view->roles = $mr->findAll('rank');
        foreach($this->view->roles as $i => $role) $this->view->roles[$i] = $role->toArray();
    }

}
	