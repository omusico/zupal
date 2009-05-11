<?

class UserController extends Zupal_Controller_Abstract
{

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
		$login_form = new Zupal_Forms_Users_Login();
		if ($login_form->isValid($this->_getAllParams()))
		{
			$values = $login_form->getValues();
			$authorizer = new Zupal_Authorizer(
				$values['username'], $values['password']
			);

			$auth = Zend_Auth::getInstance();
			$result = $auth->authenticate($authorizer);

			if ($result->isValid())
			{
				$this->_forward('index', 'index', NULL, array('message' => 'Welcome!'));
			}
			else
			{
				$this->_forward('login', 'user', NULL, array('message' => 'Sorry, bad login'));
			}
		}
		else
		{
			$this->_forward('login', 'user', NULL, array('message' => 'Sorry, bad login'));
		}
	}
}