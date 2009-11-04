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
            $game->add_user($user);
            $params = array('id' => $game->identity(), 'message' => 'Created Game ' . $game->get_title());
            $this->_forward('start', NULL, NULL, $params);
        else:
            $params = array('error' => 'You must be logged in to start a game');
            $this->_forward('index', NULL, NULL, $params);
        endif;
    }

    public function runAction()
    {        
        if(!$this->_prep()) return;

        if (!count($this->view->player->groups())):
            return $this->_forward('start');
        endif;
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

        $id = $this->_getParam('id');

        if (!$id):
            $params = array('error' => 'no game selected');
            $this->_forward('index', NULL, NULL, $params);
            return FALSE;
        endif;

        $this->view->game = Ultimatum_Model_Ultgames::getInstance()->get($id);

        if (!$this->view->game):
            $params = array('errror' => 'Cannot find game ' . $id);
            $this->_forward('index', 'index', NULL, $params);
            return FALSE;
        endif;

        $this->view->player = Ultimatum_Model_Ultplayer::for_user_game($user, $id, FALSE);

        if (!$this->view->player):
            $params = array('errror' => 'You are not a player in game ' . $id);
            $this->_forward('index', 'index', NULL, $params);
            return FALSE;
        endif;
        return TRUE;
    }
}