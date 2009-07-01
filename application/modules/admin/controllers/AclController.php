<?

class Admin_AclController
extends Zupal_Controller_Abstract
{

	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function indexAction ()
	{
		
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ roleaddvalidateAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function roleaddvalidateAction ()
	{
		$form = new Zupal_Role_Form();
		if ($form->isValid($this->_getAllParams())):
			$form->save();
			$this->_forward('roleview', NULL, NULL, array('id' => $form->get_domain()->identity(), 'message' => 'Role created'));
		else:
			$this->_forward('rolenew', NULL, NULL, array('error' => 'Cannot save role', 'reload' => 1));
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ roleviewAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function roleviewAction ()
	{
		$this->view->role = new Zupal_Roles($this->_getParam('id'));
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ roledataAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function roledataAction ()
	{
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		$role = Zupal_Roles::getInstance();
		echo $role->render_data();
	}
}