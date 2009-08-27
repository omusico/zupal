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

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ goodbyeAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function goodbyeAction () {
    }
}
