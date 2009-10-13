<?php
class Pages_IndexController extends Zupal_Controller_Abstract
{
    public function indexAction()
    {
        
    }
    public function viewAction()
    {
        $id = $this->_getParam("id",  NULL );     
        $this->view->page = Pages_Model_Zupalpages::getInstance()->get($id);
        //@TODO: add security check
    }

}
