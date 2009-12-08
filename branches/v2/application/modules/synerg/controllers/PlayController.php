<?

class Synerg_PlayController
extends Zupal_Controller_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 */
    public function init () {
        if (!$user = Model_Users::getInstance()->current_user()):
            $params = array('error' => 'You must be logged in to play Syner-G');
            $this->forward('index', 'index', NULL, $params);
            return FALSE;
        endif;
        return TRUE;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controller_dir @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

     public function controller_dir () {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }

}