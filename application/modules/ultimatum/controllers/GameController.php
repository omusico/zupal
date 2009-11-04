<?php
class Ultimatum_GameController extends Zupal_Controller_Abstract
{
    public function indexAction()
    {
        if ($user = Model_Users::current_user()):
            $this->view->nouser = false;
            $prop = array('user' => $user->identity());
            $this->view->games = Ultimatum_Model_Ultplayer::getInstance()->find($prop);
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
            $game->add_player($user);
            $params = array('id' => $game->identity(), 'message' => 'Created Game ' . $game->get_title());
            $this->_forward('run', NULL, NULL, $params);
        else:
            $params = array('error' => 'You must be logged in to start a game');
            $this->_forward('index', NULL, NULL, $params);
        endif;
    }

    public function runAction()
    {        
        $this->_prep();

        if (!count($this->view->player->groups())):
            return $this->_forward('start');
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ startAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function startAction () {
        $this->_prep();

        //@TODO: make sure the groups are not already owned! hell is other players
        $t = Ultimatum_Model_Ultgroups::getInstance()->table();
        $sql = sprintf('SELECT %s FROM %s', $t->idField(), $t->tableName());
        $ids = $t->getAdapter()->fetchCol($sql);

        $groups_ids = Zupal_Util_Array::random_set($ids, 4);

        $groups = array();

        foreach($group_ids as $id):
            $groups[] = $group = Ultimatum_Model_Ultgroups::getInstance()->get($id);
            $this->view->player->full_scan_group($group);
        endforeach;

        $this->view->groups = $groups;


    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _prep @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function _prep () {
        if ($user = Model_Users::current_user()):
            $params = array('error' => 'You must be logged in to run a game. ');
            return $this->_forward('index', 'index', NULL, $params);
        endif;

        $id = $this->_getParam('id');

        if (!$id):
            $params = array('error' => 'no game selected');
            return $this->_forward('index', NULL, NULL, $params);
        endif;

        $this->view->game = Ultimatum_Model_Ultgames::getInstance()->get($id);

        if (!$this->view->game):
            $params = array('errror' => 'Cannot find game ' . $id);
            return $this->_forward('index', 'index', NULL, $params);
        endif;

        $this->view->player = Ultimatum_Model_Ultplayer::for_user_game($user, $id, FALSE);

        if (!$this->view->player):
            $params = array('errror' => 'You are not a player in game ' . $id);
            return $this->_forward('index', 'index', NULL, $params);
        endif;
    }
}