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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ byeAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function byeAction () {
        Model_Users::getInstance()->clear_current_user();
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

            $pwMD5 = md5($password);

            $found = $ut->findOne(array('email' => $email));

            if ($found):
                $comment = array(
                    'error' => "Already have user with email $email"
                );
                $this->_forward('hi', NULL, NULL, $comment);
                return;
            endif;

            $data = array('username' => $username,
                'password' => $pwMD5,
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

/* @@@@@@@@@@@@@ EXTENSION BOILERPLATE @@@@@@@@@@@@@@ */

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controller_dir @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function controller_dir () {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ editmeAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function editmeAction () {
        $this->view->form = new Form_Zupalusers(Model_Users::current_user());
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ editmeexecuteAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * Note -- becuase the processing of the user data is inherently custom
     * it takes place in manual form here, not via form.
     */
    public function editmeexecuteAction () {
        $user = Model_Users::current_user();

        if (!$user):
            throw new Exception("No user found");
        endif;

        extract($this->_getAllParams());

        if ($username):
            $user->set_username($username);
        endif;

        if ($new_password):
            $user->set_password($password, $new_password, $new_password_2);
        endif;

        // note -- intentionally NOT saving email change!

        $user->save();
        
        $params = array('message' => 'Updated User Record');
        $this->_forward('me', NULL, NULL, $params);
    }

}
