<?php
class Ultimatum_GameController extends Zupal_Controller_Abstract
{
    public function indexAction()
    {
        $user = Model_Users::current_user();
        if ($user):
            $pi = Ultimatum_Model_Ultplayers::getInstance();
            $params = array('user' => $user->identity(), 'active' => 1, 'status' => 'active');
            if ($active_game = $pi->findOne($params, 'id DESC')):
                $params = array('game' => $active_game->game);
                return $this->_forward('run', NULL, NULL, $params);
            endif;
            $this->view->nouser = false;
            $prop = array('user' => $user->identity());
            $this->view->games = $pi->find($prop);
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
        if (!count($this->view->player->player_groups())):
            return $this->_forward('start');
        endif;
        $this->_draw_network();
        $orders = $this->view->player->pending_orders();
        $this->view->pending_orders = $orders;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _draw_network @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     *
     */

    public function _draw_network () {
        $this->view->groups = $this->view->player->player_groups();
        $this->view->scans = $this->view->player->scans();
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
        Zend_Registry::set('ultimatum_game', $game);
        if (!$player):
            $params = array('errror' => 'You are not a player in game ' . $id);
            $this->_forward('index', 'index', NULL, $params);
            return FALSE;
        endif;
        $this->view->player = $player;
        if ($player_group = $this->_getParam('player_group')):
            $this->view->player_group = Ultimatum_Model_Ultplayergroups::getInstance()->get($player_group);
        elseif ($group = $this->_getParam("group",  NULL )):
            $this->view->player_group = $player->player_group($group);
        endif;

        if ($target = $this->_getParam('target')):
            $this->view->target = Ultimatum_Model_Ultgroups::getInstance()->get($target);
        endif;
        return TRUE;
    }

        public function gamesstoreAction() {
        $pt = Ultimatum_Model_Ultplayers::getInstance();
        $pl = $pt->find(array('user' => Model_Users::current_user()->identity()));
        $data = array();
        foreach($pl as $p):
            $game = $p->get_game();
            if ($game->status == 'started'):
                $row = $game->toArray();
                $players = $game->players(TRUE);
                $turn = $game->turn(TRUE);
                $row['players'] = count($players);
                $row['turn'] = $turn;
                $data[] = $row;
            endif;
        endforeach;
        ksort($data);
        $this->_store('id', $data, 'name');
    }

    public function interactAction()
    {
        if (!$this->_prep()):
            $params = array('error' => 'Cannot load game.');
            $this->_forward('index', 'index', NULL, $params);
        elseif(!$this->view->player_group):
            $params = array('error' => 'Cannot load group');
            $this->_forward('run', NULL, NULL, $params);
        endif;
    }

    public function networkAction()
    {
        if (!$this->_prep()):
            $params = array('error' => 'Cannot load game.');
            $this->_forward('index', 'index', NULL, $params);
        elseif(!$this->view->player_group):
            $params = array('error' => 'Cannot load group');
            $this->_forward('run', NULL, NULL, $params);
        endif;
        $this->_draw_network();
    }

    public function resizeAction()
    {
        if (!$this->_prep()):
            $params = array('error' => 'Cannot load game.');
            return $this->_forward('index', 'index', NULL, $params);
        elseif(!$this->view->player_group):
            $params = array('error' => 'Cannot load group');
            return $this->_forward('run', NULL, NULL, $params);
        endif;
        $this->view->form = $form = new Ultimatum_Form_GroupResize($this->view->player_group);
        error_log(print_r($form, 1));
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ resourceexecuteAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 *
 */

    public function resizeexecuteAction () {
        if (!$this->_prep()):
            $params = array('error' => 'Cannot load game.');
            return $this->_forward('index', 'index', NULL, $params);
        elseif(!$this->view->player_group):
            $params = array('error' => 'Cannot load group');
            return $this->_forward('run', NULL, NULL, $params);
        endif;
        $form = new Ultimatum_Form_GroupResize($this->_getParam('player_group'));
        $new_values = $form->scale($this->_getAllParams());
        $order = new Ultimatum_Model_Ultplayergrouporder();
        $order->type = 'resize';
        $order->player_group = $this->view->player_group->identity();
        $order->save();
        $resize = Ultimatum_Model_Ultplayergrouporderresizes::getInstance()->get(NULL, $new_values);
        $resize->order_id = $order->identity();
        $resize->save();
        $params = array('message' => 'Group resize order given');
        $this->_forward('run', NULL, NULL, $params);
    }

    public function __call($methodName, $args) {
        parent::__call($methodName, $args);
    }

    public function moveAction()
    {
        if (!$this->_prep()):
            $params = array('error' => 'Cannot load game.');
            $this->_forward('index', 'index', NULL, $params);
        elseif(!$this->view->player_group):
            $params = array('error' => 'Cannot load group');
            $this->_forward('run', NULL, NULL, $params);
        endif;
    }

    public function orderexecuteAction()
    {
        if (!$this->_prep()):
            $this->_forward('index', 'index', NULL, array('error' => 'problem loading game'));
        endif;
        $form = new Ultimatum_Form_Ultplayergrouporder($this->_getParam('player_group'));
        $params = $this->_getAllParams();
        $params['commander'] = $this->view->player->identity();
        if ($form->isValid($params)):
            $form->save();
            $params = array('message' => 'Order Given');
            $this->_forward('run', NULL, NULL, $params);
        else:
            $params = array('error' => 'Cannot give order');
            $this->_forward('order', NULL, NULL, $params);
        endif;
    }

    public function cancelorderAction()
    {
        if (!$this->_prep()):
            $this->_forward('index', 'index', NULL, array('error' => 'problem loading game'));
        endif;
        $order = $this->_getParam("order",  NULL );
        $po = Ultimatum_Model_Ultplayergrouporder::getInstance()->get($order);
        if (!$po->player_group()->player()->identity() == $this->view->player->identity()):
            $params = array('error' => 'cannot cancel order ' . $order . ': doesn\'t refer to one of your groups');
        else:
            $po->cancel();
            $params = array('message' => 'Cancelled order ' . $po);
         endif;
      $this->_forward('run', NULL, NULL, $params);
   }

    public function attackAction()
    {
        if (!$this->_prep()):
            return $this->_forward('index', 'index', NULL, array('error' => 'problem loading game'));
        elseif (!$this->view->target):
            $params = array('error' => 'Cannot find target');
            return $this->_forward('run', NULL, NULL, $params);
        endif;

       $pid = $this->view->player->identity();
       $gid = $this->view->target->identity();

       $params = array('group_id' => $gid, 'player' => $pid);

       $k = Ultimatum_Model_Ultplayergroupknowledge::getInstance()->findOne($params);
       if ($k->isSaved()):
            $this->view->scan = $k;
       else:
            $param = array('error' => 'strange, what group ' . $gid . '?');
            $this->_forward('run', NULL, NULL, $param);
       endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ attackexecuteAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function attackexecuteAction () {
        if (!$this->_prep()):
            $this->_forward('index', 'index', NULL, array('error' => 'problem loading game'));
        elseif(!$this->view->player_group):
            $params = array('error' => 'Cannot load group');
            $this->_forward('run', NULL, NULL, $params);
        endif;

        $ord = new Ultimatum_Model_Ultplayergrouporder();

        $ord->player_group = $this->view->player_group->identity();
        $ord->type = 'attack';
        if ($this->_getParam('repeat')):
            $ord->repeat = 'iterate';
            $ord->iterations = min(1, (int)$this->_getParam('repeat_count'));
        endif;
        $ord->target = $this->_getParam('target');
        $ord->save();

        $attack = new Ultimatum_Model_Ultplayergrouporderattacks();
        $attack->order_id = $ord->identity();
        $attack->reduceprop = $this->_getParam('reduceprop');
        $attack->reduceprop_property = (int) $this->_getParam('reduceprop_property');
        $attack->reduceprop_strength = (int) $this->_getParam('reduceprop_strength');
        $attack->loss = $this->_getParam('loss');
        // $attack->loss_count = $this->_getParam('loss_strength_count');
        $attack->loss_strength = $this->_getParam('loss_strength');
        $attack->loss_strength_count = $this->_getParam('loss_strength_count');
        $attack->payoff = $this->_getParam('payoff');
        $attack->payoff_count = $this->_getParam('payoff_count');

        $attack->save();

        $params = array('message' => 'Scheduled attack');
        $this->_forward('run', NULL, NULL, $params);
    }
}

