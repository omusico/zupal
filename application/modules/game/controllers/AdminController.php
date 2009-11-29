<?

class Game_AdminController
extends Zupal_Controller_Abstract
{

    public function preDispatch() {
        $u = Model_Users::current_user();
        if (!$u || ! $u->can('game_admin_game')):
            $param = array('error' => 'This area is reserved for administrators');
            return $this->_forward('insecure', 'error', 'administer', $param);
        endif;
        $this->_helper->layout->setLayout('admin');
        parent::preDispatch();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function indexAction () {
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

}