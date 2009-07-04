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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ reseditAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function reseditAction ()
	{
		$this->view->form = new Zupal_Resource_Form($this->_getParam('id'));
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ reseditAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function roleeditAction ()
	{
		$this->view->form = new Zupal_Role_Form($this->_getParam('id'));
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ resaddvalidateAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function resaddvalidateAction ()
	{
		$form = new Zupal_Resource_Form();
		if ($form->isValid($this->_getAllParams())):
			$form->save();
			$this->_forward('resview', NULL, NULL, array('id' => $form->get_domain()->identity(), 'message' => 'Role created'));
		else:
			$this->_forward('resnew', NULL, NULL, array('error' => 'Cannot save res', 'reload' => 1));
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ resupdatevalidateAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function resupdatevalidateAction ()
	{
		$form = new Zupal_Resource_Form($this->_getParam('id'));
		if ($form->isValid($this->_getAllParams())):
			$form->save();
			$id = $form->get_domain()->identity();
			$this->_forward('resview', NULL, NULL, array('id' => $id));
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ roleupdatevalidateAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function roleupdatevalidateAction ()
	{
		$form = new Zupal_Role_Form($this->_getParam('id'));
		if ($form->isValid($this->_getAllParams())):
			$form->save();
			$id = $form->get_domain()->identity();
			$this->_forward('roleview', NULL, NULL, array('message' => 'role saved', 'id' => $id));
		else:
			$this->_forward('roleedit', NULL, NULL, array('error' => 'cannot save role', 'reload' => 1, 'id' => $id));
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ resviewAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function resviewAction ()
	{
		$this->view->res = new Zupal_Resources($this->_getParam('id'));
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
		echo $role->render_data(array());
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ roledataAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function resdataAction ()
	{
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		$res = Zupal_Resources::getInstance();
		echo $res->render_data(array());
	}
}