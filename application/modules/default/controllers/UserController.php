<?

class UserController extends Zupal_Controller_Abstract
{
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 */
    public function indexAction () {
        if (Model_Users::current_user()):
            $this->_forward('me');
        else:
            $this->_forward('hi');
        endif;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ meAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function meAction () {

    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ hiAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function hiAction () {
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ registerAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function registerAction () {
        $register_form = new Form_Userregister();

        if ($register_form->isValid($this->_getAllParams())):
            $ut = Model_Users::getInstance();

            $email = $this->_getParam('email');
            $username = $this->_getParam('username');
            $password = $this->_getParam('password');
            $pw2 = $this->_getParam('password2');

            if ($password != $pw2):
                $comment = array('error' => 'Passwords mismatch');
                return $this->_forward('hi', NULL, NULL, $comment);
            endif;

            $pwmp5 = md5($password);

            $found = $ut->findOne(array('email' => $email));

            if ($found):
                $comment = array(
                    'error' => "Already have user with email $email"
                );
                $this->_forward('hi', NULL, NULL, $comment);
                return;
            endif;

            $data = array('username' => $username,
                'password' => $pwmp5,
                'email' => $email);
            $user = $ut->get(NULL, $data);
            $user->save();
            $user->password = Zupal_Authorizer::encrypt_password(
                $password, $user->identity());
            $user->save();

            $this->_forward(
               'hi', NULL, NULL, array('message' => 'User Registered.')
            );
            
        else:
            $comment = array(
                'reload_register' => true,
                'error' => "Incomplete Form"
            );
            $this->_forward('hi', NULL, NULL, $comment);

        endif;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ goodbyeAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function goodbyeAction () {
    }

    /**
     * Note this is just an action handler --
     * it either forks back to loginAction or to the root index.
     *
     */
    public function loginAction() {
	$login_form = new Form_Userlogin();
	if ($login_form->isValid($this->_getAllParams())) {
	    $authorizer = new Zupal_Authorizer(
		$login_form->username->getValue(), $login_form->password->getValue()
	    );

	    $auth = Zend_Auth::getInstance();
	    $result = $auth->authenticate($authorizer);

	    if ($result->isValid()) {
		$this->_forward('me', NULL, NULL, array('message' => 'Logged In'));
	    }
	    else {
		$this->_forward('hi', NULL, NULL, array('message' => 'Sorry, bad password'));
	    }
	}
	else {
	    $this->_forward('hi', NULL, NULL, array('message' => 'Sorry, bad login'));
	}
    }
}
