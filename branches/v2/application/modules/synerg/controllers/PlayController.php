<?

class Synerg_PlayController
extends Zupal_Controller_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 *
 * @var Model_Users
 */
    private $_user;
    private $_user_session = NULL;

    public function init () {
        if (!$this->_user = Model_Users::getInstance()->current_user()):
            $params = array('error' => 'You must be logged in to play Syner-G');
            $this->forward('index', 'index', NULL, $params);
            return FALSE;
        endif;

        $this->_active_session = Synerg_Model_Gamesessions::getInstance()->active_session();

        return parent::init();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controller_dir @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

     public function controller_dir () {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function indexAction () {
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ startAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function startAction () {
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ activaterAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *@TODO: better error trapping
     */
    public function activateAction () {
        $sid = $this->_getParam('session');
        $uid = $this->_user->identity();

        $user_session = Game_Model_Gamesessions::getInstance()->get($sid);
        $user_session->activate($uid);
        $this->_active_session = $user_session;

        $params = array('message' => 'Session Activated');

        $this->forward('index', NULL, NULL, $params);
    }

}