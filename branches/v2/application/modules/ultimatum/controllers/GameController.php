<?php
class Ultimatum_GameController extends Zupal_Controller_Abstract
{
    public function indexAction()
    {
        if ($user = Model_Users::current_user()):
            $this->view->nouser = false;
            $prop = array('user' => $user->identity());
            $this->view->games = Ultimatum_Model_Ultplayers::getInstance()->find($prop);
        else:
            $this->view->nouser = true;
        endif;
    }

    public function newAction()
    {
        // note -- title is not required -- a generaic numbered game will be created in its absence.
        if ($user = Model_Users::current_user()):
            $title = $this->_getParam("title",  NULL );
            $game = new Ultimatum_Model_Ultgames();
            $game->set_title($title);
            $game->save();
            $game->add_user($user)->activate();
            $params = array('game' => $game->identity(), 'message' => 'Created Game ' . $game->get_title());
            
            $this->_forward('start', NULL, NULL, $params);
        else:
            $params = array('error' => 'You must be logged in to start a game');
            $this->_forward('index', NULL, NULL, $params);
        endif;
    }

    public function runAction()
    {        
        if(!$this->_prep()):
            return $this->_forward('index', 'index', NULL, array('error' => 'Problem playing Ultimatum'));
        endif;

        if (!count($this->view->player->groups())):
            return $this->_forward('start');
        endif;

        $this->_draw_network();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _draw_network @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     *
     */
    public function _draw_network () {
        $this->view->player_groups = $this->view->player->groups(TRUE);
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ startAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function startAction () {
        if (!$this->_prep()) return;

        //@TODO: make sure the groups are not already owned! hell is other players
        $t = Ultimatum_Model_Ultgroups::getInstance()->table();
        $sql = sprintf('SELECT %s FROM %s', $t->idField(), $t->tableName());
        $ids = $t->getAdapter()->fetchCol($sql);

        $group_ids = Zupal_Util_Array::random_set($ids, 4);


        foreach($group_ids as $id):
            $groups[] = $group = Ultimatum_Model_Ultgroups::getInstance()->get($id);
            $scans[] = $this->view->player->full_scan_group($group);
        endforeach;

        $this->view->groups = $groups;
        $this->view->scans = $scans;


    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ startexecuteAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function startexecuteAction () {
        if (!$this->_prep()):
            return;
        endif;

        $this->view->player->acquire($this->_getParam('group'));
        $this->_forward('run');
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _prep @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function _prep () {
        $user = Model_Users::current_user();
        if (!$user):
            $params = array('error' => 'You must be logged in to run a game. ');
            $this->_forward('index', 'index', NULL, $params);
            return FALSE;
        endif;

        $game_id = $this->_getParam('game');

        // either actively select game or reactivate last played game
        if ($game_id):
            $game = Ultimatum_Model_Ultgames::getInstance()->get($game_id);
            $player = Ultimatum_Model_Ultplayers::for_user_game($user, $game)->activate();
        else:
            $player = Ultimatum_Model_Ultplayers::user_active_player($user);
            if (!$player):
                $params = array('error' => 'cannot find active game');
                $this->_forward('index', 'index', NULL, $params);
                return FALSE;
            endif;
            $game = $player->get_game();
        endif;
        
        $this->view->game = $game;

       if (!$player):
            $params = array('errror' => 'You are not a player in game ' . $id);
            $this->_forward('index', 'index', NULL, $params);
            return FALSE;
        endif;

        $this->view->player = $player;

        return TRUE;
    }
}