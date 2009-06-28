<?

class People_UsersController extends Zupal_Controller_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ viewAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function viewAction ()
	{
		$this->view->user = new Zupal_Users($this->_getParam('id'));
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ updatevalidateAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function updatevalidateAction ()
	{
		$form = new Zupal_User_Form($this->_getParam('person_id'));
		if ($form->is_valid($this->_getAllParams())):
			$form->save();
			$this->_redirect('view', NULL, NULL, array('message' => 'User saved',
			'id' => $form->get_domain()->identity()));
		else:
			$this->_redirect('edit', NULL, NULL, array('error' => 'Cannot save user', 'reload' => true));
		endif;
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ editAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function editAction ()
	{
		$this->view->user = new Zupal_Users($this->_getParam('id'));
		$this->view->form = new Zupal_User_form($this->_getParam('id'));
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ dataAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function dataAction ()
	{
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		echo Zupal_Users::getUserInstance()->render_data(array(), 'username');
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function indexAction ()
	{
		$this->view->form = new Zupal_User_Form();
		$this->view->user_form = new Zupal_User_Finder();
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ logoutAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function logoutAction()
	{
		Zend_auth::getInstance()->clearIdentity();
		$this->_forward('index', NULL, NULL, array('message' => 'You have been logged out. Come Again.'));
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ loginAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function loginAction()
	{
		$login_form = new Zupal_People_Loginform();
		if ($this->_hasParam('username')):
			$login_form->isValid($this->_getAllParams());
		endif;
		$this->view->form = $login_form;
	}

	/**
	 * Note this is just an action handler --
	 * it either forks back to loginAction or to the root index.
	 *
	 */
	public function loginvalidateAction()
	{
		$login_form = new Zupal_People_Loginform();
		if ($login_form->isValid($this->_getAllParams()))
		{
			$values = $login_form->getValues();
			$authorizer = new Zupal_People_Authorizer(
				$values['username'], $values['password']
			);

			$auth = Zend_Auth::getInstance();
			$result = $auth->authenticate($authorizer);

			if (!$result->isValid())
			{
				$this->_forward('login', NULL, NULL, array('message' => 'Sorry, bad login'));
			}
		}
		else
		{
			$this->_forward('login', NULL, NULL, array('message' => 'Sorry, bad login'));
		}
	}
}