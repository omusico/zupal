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
        $this->view->stub = "stub";
    }

    public function rolesstoreAction() {
        $this->_helper->layout->disableLayout();
        $mr = Model_Roles::getInstance();
        $this->view->roles = $mr->findAll('rank');
        foreach($this->view->roles as $i => $role) $this->view->roles[$i] = $role->toArray();
    }

    public function resourcesstoreAction() {
        $this->_helper->layout->disableLayout();
        $mr = Model_Resources::getInstance();
        $this->view->resources = $mr->findAll(array('module', 'rank'));
        foreach($this->view->resources as $i => $role) $this->view->resources[$i] = $role->toArray();
    }

    public function resourcesAction() {
        $this->view->resource_grid = $resource_grid;
        $this->view->resource_store = $resource_store;
        $this->view->resource_uri = '/administer/resources/data';
        $this->view->resource_id = 'resource_id';
    }

    public function resourcesnewAction() {
        $this->view->form = new Administer_Form_Zupalresources();
    }

    public function resourcesaveAction() {
        $form = new Administer_Form_Zupalresources();
        if ($form->isValid($this->_getAllParams())):
            $form->save();
            $params = array('id' => $form->get_domain()->identity());
            $this->_forward('resourceview', NULL, NULL, $params);
    endif;
    }

    public function resourceviewAction() {
        $id = $this->_getParam("id",  NULL );
        
        $this->view->id = $id;
        $this->view->resource = Model_Resources::getInstance()->get($id);;
    }
	
    public function aclAction()
    {
        $orientation = $this->_getParam("orientation",  NULL );  
        $this->view->orientation = $orientation;    
        $this->view->roles = Model_Roles::getInstance()->findAll(array('rank', 'role_id'));
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ aclsetAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function aclsetAction () {
        $this->_helper->layout->disableLayout();
       extract($this->_getAllParams());

       Model_Acl::getInstance()->set_acl($resource, $role, $allow);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ aclstoreAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function aclstoreAction () {
        $this->_helper->layout->disableLayout();
        $this->view->roles = Model_Roles::getInstance()->findAll('role_id');
        $this->view->resources = Model_Resources::getInstance()->findAll('resource_id');
    }
    
}
	