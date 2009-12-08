<?

class Game_IndexController
extends Zupal_Controller_Abstract
{


/* @@@@@@@@@@@@@ EXTENSION BOILERPLATE @@@@@@@@@@@@@@ */

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controller_dir @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function controller_dir () {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ usergamesAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function usergamesAction () {
        $data = array();
        $game = $this->_getParam('game', '');
        if ($user = Model_Users::getInstance()->current_user()):
            $params = array('user' => $user->identity());

            $user_sessions = Game_Model_Gamesessionplayers::getInstance()->find($params);
            foreach($user_sessions as $us):
                $session = $us->session();
                if ($game && strcasecmp($game, $session->name)):
                    continue;
                endif;
                $data[] = $session;
            endforeach;
        endif;

        $out = array();

        foreach($data as $session):
            $out[] = $session->toArray(TRUE);
        endforeach;

        $this->_store('id', $out, 'name');

    }
}