<?

class Ultimatum_Game_AttackAction
extends Zupal_Controller_Action_Abstract{

    public function run() {
            $c = $this->get_controller();
        if (!$c->_prep()):
            return $c->forward('index', 'index', NULL, array('error' => 'problem loading game'));
        elseif (!$c->view->target):
            $params = array('error' => 'Cannot find target');
            return $c->forward('run', NULL, NULL, $params);
        endif;
        $pid = $c->view->player->identity();
        $gid = $c->view->target->identity();
        $params = array('group_id' => $gid, 'player' => $pid);
        $k = Ultimatum_Model_Ultplayergroupknowledge::getInstance()->findOne($params);
        if ($k->isSaved()):
            $c->view->scan = $k;
        else:
            $param = array('error' => 'strange, what group ' . $gid . '?');
            $c->forward('run', NULL, NULL, $param);
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ attackexecuteAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function respond () {
            $c = $this->get_controller();
        if (!$c->_prep()):
            $c->forward('index', 'index', NULL, array('error' => 'problem loading game'));
        elseif(!$c->view->player_group):
            $params = array('error' => 'Cannot load group');
            $c->forward('run', NULL, NULL, $params);
        endif;
        $ord = new Ultimatum_Model_Ultplayergrouporders();
        $ord->player_group = $c->view->player_group->identity();
        $ord->type = 'attack';
        if ($c->_getParam('repeat')):
            $ord->repeat = 'iterate';
            $ord->iterations = min(1, (int)$c->_getParam('repeat_count'));
        endif;
        $ord->target = $c->_getParam('target');
        $ord->save();
        $attack = new Ultimatum_Model_Ultplayergrouporderattacks();
        $attack->order_id = $ord->identity();
        $attack->reduceprop = $c->_getParam('reduceprop');
        $attack->reduceprop_property = (int) $c->_getParam('reduceprop_property');
        $attack->reduceprop_strength = (int) $c->_getParam('reduceprop_strength');
        $attack->loss = $c->_getParam('loss');
        // $attack->loss_count = $c->_getParam('loss_strength_count');
        $attack->loss_strength = $c->_getParam('loss_strength');
        $attack->loss_strength_count = $c->_getParam('loss_strength_count');
        $attack->payoff = $c->_getParam('payoff');
        $attack->payoff_count = $c->_getParam('payoff_count');
        $attack->save();
        $params = array('message' => 'Scheduled attack');
        $c->forward('run', NULL, NULL, $params);
    }
}