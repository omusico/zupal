<?

class Synerg_GameController
extends Zupal_Controller_Abstract {


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 */
    public function init () {
        if (!$user = Model_Users::getInstance()->current_user()):
            $params = array('error' => 'You must be logged in to play Syner-G');
            $this->forward('index', 'index', NULL, $params);
        endif;
        parent::init();
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 */
    public function indexAction () {
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controller_dir @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

     public function controller_dir () {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ startAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function startAction () {
    }
}