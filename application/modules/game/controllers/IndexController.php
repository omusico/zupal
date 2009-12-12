<?

class Game_IndexController
extends Zupal_Controller_Abstract {


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

        $user = $this->_getParam('user');
        if ($user):
            $user = Model_Users::getInstance()->get($user);
        else:
            $user = Model_Users::current_user();
        endif;

        $params = array('user' => $user->identity());

        $game_type = $game ? Game_Model_Gametypes::game_type($game) : NULL;

        $user_sessions = Game_Model_Gamesessionplayers::getInstance()->find($prarams);

        foreach($user_sessions as $us):
            $session = $us->session();
            if ($game_type && (!$session->is_game_type($game_type))):
                continue;
            endif;
            $out[] = $session->toArray(TRUE);
        endforeach;

        $this->_store('id', $out, 'name');
    }
}