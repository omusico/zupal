<?php
class Administer_UsersController extends Zupal_Controller_Abstract
{
    public function init()
    {
        $this->_helper->layout->setLayout('admin');
        
        
        
                parent::init();
    }
    /**
     * 
     */
    public function indexAction()
    {
        $uf = new Administer_Form_Userfind();
        
        
        
                $this->view->find_user_form = $uf;
        
        
        
                $this->view->register_user_form = new Form_Userregister();
    }
    public function rolesAction()
    {
    }
    public function rolesstoreAction()
    {
        $this->_helper->layout->disableLayout();
        
                $mr = Model_Roles::getInstance();
        
                $this->view->roles = $mr->findAll('rank');
        
                foreach($this->view->roles as $i => $role) $this->view->roles[$i] = $role->toArray();
    }
    public function resourcesAction()
    {
        $this->view->resource_grid = $resource_grid;
        $this->view->resource_store = $resource_store;
        $this->view->resource_uri = '/administer/resources/data';
        $this->view->resource_id = 'resource_id';
    }

}
