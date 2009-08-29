<?

class Administer_UsersController
extends Zupal_Controller_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 */
    public function indexAction () {
       $uf = new Administer_Form_Userfind();
        $this->view->find_user_form = $uf;

        $this->view->register_user_form = new Form_Userregister();
    }

}