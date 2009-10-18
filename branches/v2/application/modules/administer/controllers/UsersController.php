<?php
class Administer_UsersController extends Zupal_Controller_Abstract {
    public function init() {
        $this->_helper->layout->setLayout('admin');
        parent::init();
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ preDispatch @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *  Protect users from unathorized access.
 * //@TODO: remove site_admin clause. 
 *
 * @return void
 */
    public function preDispatch () {
        $u = Model_Users::current_user();

        if (!$u || ! ($u->can('site_admin') || $u->can('user_admin'))):
            $param = array('error' => 'This area is reserved for administrators');
            $this->_forward('insecure', 'error', 'administer', $param);
        endif;
        parent::preDispatch();
    }

    /**
     *
     */

    public function indexAction() {
        $uf = new Administer_Form_Userfind();
        $uf->setAction('/administer/users/findexecute');
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

    public function aclAction() {
        $orientation = $this->_getParam("orientation",  NULL );
        $this->view->orientation = $orientation;
        $this->view->roles = Model_Roles::getInstance()->findAll(array('rank', 'role_id'));
    }

    /**
     *
     */

    public function aclsetAction() {
        $this->_helper->layout->disableLayout();
        extract($this->_getAllParams());
        Model_Acl::getInstance()->set_acl($resource, $role, $allow);
    }

    /**
     *
     */

    public function aclstoreAction() {
        $this->_helper->layout->disableLayout();
        $this->view->roles = Model_Roles::getInstance()->findAll('role_id');
        $this->view->resources = Model_Resources::getInstance()->findAll('resource_id');
    }

    public function resourceseditAction() {
        $resource_id = $this->_getParam("resource_id",  NULL );
        $resource = new Model_Resources($resource_id);
        $this->view->form = new Administer_Form_Zupalresources($resource);
        $this->view->resource_id = $resource_id;
    }

    public function resourceditexecuteAction() {
        $form = new Administer_Form_Zupalresources();
        if ($form->isValid($this->_getAllParams())):
            $form->save();
        else:
            $params = array('error' => 'cannot save resources', 'reload' => 1);
            $this->_forward('resourceedit', NULL, NULL, $params);
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ findexecuteAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     */

    public function findexecuteAction () {
        $this->view->search = trim($this->_getParam('search'));
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ findexecuteAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * tnice antoinette  510 789 6613
     * renee 931-1775
     */

    public function findstoreAction () {
        $search = trim($this->_getParam('search'));
        $t = Model_Users::getInstance()->table();
        $rt = Model_Roles::getInstance()->table();
        $sql = $t->getAdapter()
        ->select()
        ->from(array('u' => $t->tableName()))
        ->join(array('r' => $rt->tableName()),
            'u.role = r.' . $rt->idField(),
            array('role_name' => 'r.title')
        )
        ->where('username LIKE ?', "%$search%")
        ->orWhere('email LIKE ?', "%$search%");
        $data = new Zend_Dojo_Data(
            'id',
            Model_Users::getInstance()->table()->getAdapter()->fetchAssoc($sql->assemble())
            );
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        echo $data;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ editAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     */

    public function editAction () {
        $id = $this->_getParam('id');
        $user = Model_Users::getInstance()->get($id);
        if (!$user):
            $params = array('error' => "cannot find user id $id");
            $this->_forward('index', NULL, NULL, $params);
        endif;
        $this->view->form = new Administer_Form_Zupalusers($user);
    }

    public function roleseditAction()
    {
        $id = $this->_getParam("role_id",  NULL );
        $this->view->role = Model_Roles::getInstance()->get($id);

    }

}

