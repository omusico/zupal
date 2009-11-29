<?php
class Ultimatum_GameController
extends Zupal_Controller_Abstract {
    public function indexAction() {
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

    public function newAction() {
    // note -- title is not required -- a generaic numbered game will be created in its absence.
        if ($user = Model_Users::current_user()):
            $title = $this->_getParam("title",  NULL );
            $game = new Ultimatum_Model_Ultgames();
            $game->set_title($title);
            $game->save();
            $game->add_user($user);
            $params = array('game' => $game->identity(), 'message' => 'Created Game ' . $game->get_title());
            $this->_forward('start', NULL, NULL, $params);
        else:
            $params = array('error' => 'You must be logged in to start a game');
            $this->_forward('index', NULL, NULL, $params);
    endif;
    }

//    public function runAction() {
//        if(!$this->_prep()):
//            return $this->_forward('index', 'index', NULL, array('error' => 'Problem playing Ultimatum'));
//        endif;
//        if (!count($this->view->player->player_groups())):
//            return $this->_forward('start');
//        endif;
//        $this->_draw_network();
//        $orders = $this->view->player->pending_orders();
//        $this->view->pending_orders = $orders;
//    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _draw_network @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function _draw_network () {
        $this->view->groups = $this->view->player->player_groups();
        $this->view->scans = $this->view->player->scanned_groups();
    }

///* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ startAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
///**
// * At this point the game has been created and registered - but the player has not chosen a group.
// * Note -- if they walk away they will be a player without a group -- not a good thing!
// */
//    public function startAction () {
//        if (!$this->_prep()):
//            $params = array('error' => 'Cannot start game');
//            return $this->_forward('index', NULL, NULL, $params);
//        endif;
//
//        //@TODO: make sure the groups are not already owned! hell is other players
//        $t = Ultimatum_Model_Ultgroups::getInstance()->table();
//        $sql = sprintf('SELECT %s FROM %s', $t->idField(), $t->tableName());
//        $ids = $t->getAdapter()->fetchCol($sql);
//        $group_ids = Zupal_Util_Array::random_set($ids, 4);
//
//        $game_id = $this->game->identity();
//
//        foreach($group_ids as $id):
//            $groups[] = $group = Ultimatum_Model_Ultgroups::getInstance()->get($id);
//
//            $gamegroup = new Ultimatum_Model_Ultgamegroups();
//            $gamegroup->game = $game_id;
//            $gamegroup->group_id = $id;
//            $gamegroup->save(); // note -- will be saved with no owner.
//            $gamegroup->start_size();
//
//            $scans[] = $this->view->player->full_scan_group($group);
//        endforeach;
//
//        $this->view->groups = $groups;
//        $this->view->scans = $scans;
//    }
//
///* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ startexecuteAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
//
//    /**
//     *
//     */
//
//    public function startexecuteAction () {
//        if (!$this->_prep()):
//            return;
//        endif;
//        $this->view->player->acquire($this->_getParam('group'));
//        $this->_forward('run');
//    }

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

        // at this point the player and game objects should be set.
        
        if (!$player):
            $params = array('errror' => 'You are not a player in game ' . $id);
            $this->_forward('index', 'index', NULL, $params);
            return FALSE;
        endif;
        
        $this->view->game = $game;
        $game->activate();
        $this->view->player = $player;

        // there may or may not be a player group specified by paramerters.
        // player_group refers to the group indirectly by the game group id.
        // group refers to the group id directly.
        
        if ($player_group = $this->_getParam('player_group')):
            $this->view->player_group = Ultimatum_Model_Ultgamegroups::getInstance()->get($player_group);
        elseif ($group = $this->_getParam("group",  NULL )):
            $this->view->player_group = $player->player_group($group);
        endif;

        // there may or may not be a target.
        // the target id is the group id NOT the game group id.

        if ($target = $this->_getParam('target')):
            $this->view->target = $target_obj = Ultimatum_Model_Ultgroups::getInstance()->get($target);
            $this->view->target_scan = $player->get_scan($target);
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
/*
    public function interactAction() {
        if (!$this->_prep()):
            $params = array('error' => 'Cannot load game.');
            $this->_forward('index', 'index', NULL, $params);
        elseif(!$this->view->player_group):
            $params = array('error' => 'Cannot load group');
            $this->_forward('run', NULL, NULL, $params);
    endif;
    } */

    public function networkAction() {
        if (!$this->_prep()):
            $params = array('error' => 'Cannot load game.');
            $this->_forward('index', 'index', NULL, $params);
        elseif(!$this->view->player_group):
            $params = array('error' => 'Cannot load group');
            $this->_forward('run', NULL, NULL, $params);
        endif;
        $this->_draw_network();
    }

    public function resizeAction() {
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
        $order = new Ultimatum_Model_Ultplayergrouporders();
        $order->type = 'resize';
        $order->player_group = $this->view->player_group->identity();
        $order->save();
        $resize = Ultimatum_Model_Ultplayergrouporderresizes::getInstance()->get(NULL, $new_values);
        $resize->order_id = $order->identity();
        $resize->save();
        $params = array('message' => 'Group resize order given');
        $this->_forward('run', NULL, NULL, $params);
    }

    public function moveAction() {
        if (!$this->_prep()):
            $params = array('error' => 'Cannot load game.');
            $this->_forward('index', 'index', NULL, $params);
        elseif(!$this->view->player_group):
            $params = array('error' => 'Cannot load group');
            $this->_forward('run', NULL, NULL, $params);
    endif;
    }


    public function cancelorderAction() {
        if (!$this->_prep()):
            $this->_forward('index', 'index', NULL, array('error' => 'problem loading game'));
        endif;
        $order = $this->_getParam("order",  NULL );
        $po = Ultimatum_Model_Ultplayergrouporders::getInstance()->get($order);
        if (!$po->player_group()->player()->identity() == $this->view->player->identity()):
            $params = array('error' => 'cannot cancel order ' . $order . ': doesn\'t refer to one of your groups');
        else:
            $po->cancel();
            $params = array('message' => 'Cancelled order ' . $po);
        endif;
        $this->_forward('run', NULL, NULL, $params);
    }
//
//    public function attackAction() {
//        if (!$this->_prep()):
//            return $this->_forward('index', 'index', NULL, array('error' => 'problem loading game'));
//        elseif (!$this->view->target):
//            $params = array('error' => 'Cannot find target');
//            return $this->_forward('run', NULL, NULL, $params);
//        endif;
//        $pid = $this->view->player->identity();
//        $gid = $this->view->target->identity();
//        $params = array('group_id' => $gid, 'player' => $pid);
//        $k = Ultimatum_Model_Ultplayergroupknowledge::getInstance()->findOne($params);
//        if ($k->isSaved()):
//            $this->view->scan = $k;
//        else:
//            $param = array('error' => 'strange, what group ' . $gid . '?');
//            $this->_forward('run', NULL, NULL, $param);
//        endif;
//    }
//
///* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ attackexecuteAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
//
//    /**
//     *
//     */
//
//    public function attackexecuteAction () {
//        if (!$this->_prep()):
//            $this->_forward('index', 'index', NULL, array('error' => 'problem loading game'));
//        elseif(!$this->view->player_group):
//            $params = array('error' => 'Cannot load group');
//            $this->_forward('run', NULL, NULL, $params);
//        endif;
//        $ord = new Ultimatum_Model_Ultplayergrouporders();
//        $ord->player_group = $this->view->player_group->identity();
//        $ord->type = 'attack';
//        if ($this->_getParam('repeat')):
//            $ord->repeat = 'iterate';
//            $ord->iterations = min(1, (int)$this->_getParam('repeat_count'));
//        endif;
//        $ord->target = $this->_getParam('target');
//        $ord->save();
//        $attack = new Ultimatum_Model_Ultplayergrouporderattacks();
//        $attack->order_id = $ord->identity();
//        $attack->reduceprop = $this->_getParam('reduceprop');
//        $attack->reduceprop_property = (int) $this->_getParam('reduceprop_property');
//        $attack->reduceprop_strength = (int) $this->_getParam('reduceprop_strength');
//        $attack->loss = $this->_getParam('loss');
//        // $attack->loss_count = $this->_getParam('loss_strength_count');
//        $attack->loss_strength = $this->_getParam('loss_strength');
//        $attack->loss_strength_count = $this->_getParam('loss_strength_count');
//        $attack->payoff = $this->_getParam('payoff');
//        $attack->payoff_count = $this->_getParam('payoff_count');
//        $attack->save();
//        $params = array('message' => 'Scheduled attack');
//        $this->_forward('run', NULL, NULL, $params);
//    }

//    public function orderAction() {
//        if (!$this->_prep()):
//            $this->_forward('index', 'index', NULL, array('error' => 'problem loading game'));
//        elseif(!$this->view->player_group):
//            $params = array('error' => 'Cannot load group');
//            return $this->_forward('run', NULL, NULL, $params);
//        endif;
//        $this->_draw_network();
//        $order = $this->_getParam("order",  NULL );
//        $this->view->order_type = Ultimatum_Model_Ultplayergroupordertypes::getInstance()->get($order);
//    }
//
//
//    public function orderexecuteAction() {
//        if (!$this->_prep()):
//            $this->_forward('index', 'index', NULL, array('error' => 'problem loading game'));
//        endif;
//        $form = new Ultimatum_Form_Ultplayergrouporder($this->_getParam('player_group'));
//        $params = $this->_getAllParams();
//        $params['commander'] = $this->view->player->identity();
//        if ($form->isValid($params)):
//            $form->save();
//            $data = $form->get_domain()->toArray();
//            error_log(__METHOD__ . ': saving order ' . print_r($data, 1) . ' from params ' . print_r($params, 1));
//            $params = array('message' => 'Order Given');
//            return $this->_forward('run', NULL, NULL, $params);
//        else:
//            $params = array('error' => 'Cannot give order');
//            return $this->_forward('order', NULL, NULL, $params);
//    endif;
//    }

    public function nextturnAction() {
        if (!$this->_prep()):
            $this->_forward('index', 'index', NULL, array('error' => 'problem loading game'));
        endif;;

        $user = Model_Users::current_user();
        if ($user && $user->can('ultimatum_manage')):
            $this->view->game->next_turn();
    endif;

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

