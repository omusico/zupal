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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ grantAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function grantAction ()
	{
		$res_id = $this->_getParam('res');
		$role_id = $this->_getParam('role');
		$grant = Zupal_Grants::getInstance()->findOne(array('resource' => $res_id, 'role' => $role_id));
		
		switch($this->_getParam('mode')):
			case ('choose'):
				$this->view->res = $res = new Zupal_Resources($res_id);
				$this->view->role = $role = new Zupal_Roles($role_id);
				$this->view->from = $this->_getParam('from');
				$this->view->form = new Zupal_Grant_Form($res_id, $role_id);
			break;

			case 'unlocked':
				$grant->allow = 0;
				$grant->save();
				$this->_forward('grants');
			break;

			case 'locked':
				$grant->allow = 1;
				$grant->save();
				$this->_forward('grants');
			break;

			case 'delete':
				$grant->delete();
				$this->_forward('grants');
			break;

		endswitch;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ grantvalidateAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function grantvalidateAction ()
	{
		$res = $this->_getParam('res');
		$role = $this->_getParam('role');
		$allow = $this->_getParam('allow');

		$grant = Zupal_Grants::getInstance()->findOne(array('resource' => $res, 'role' => $role));
		if (!$grant):
			$grant = new Zupal_Grants();
			$grant->resource = $res;
			$grant->role = $role;
		endif;

		if (strcasecmp('null', $allow)):
			$grant->allow = $allow;
			$grant->save();
		else:
			$grant->delete();
		endif;

		$this->_forward('grants');
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ grantAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function grantsAction ()
	{
		$this->view->res = Zupal_Resources::getInstance()->findAll(NULL, 'id');
		$this->view->roles = Zupal_Roles::getInstance()->findAll(NULL, 'id');
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