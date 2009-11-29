<?php
/**
 * Description of StartAction
 *
 * @author bingomanatee
 */
class Ultimatum_Game_StartAction
extends Zupal_Controller_Action_Abstract {

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ startAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 * At this point the game has been created and registered - but the player has not chosen a group.
 * Note -- if they walk away they will be a player without a group -- not a good thing!
 */
    public function run () {
        $c = $this->get_controller();
        if (!$c->_prep()):
            $params = array('error' => 'Cannot start game');
            return $this->forward('index', NULL, NULL, $params);
        endif;

        //@TODO: make sure the groups are not already owned! hell is other players
        $t = Ultimatum_Model_Ultgroups::getInstance()->table();
        $sql = sprintf('SELECT %s FROM %s', $t->idField(), $t->tableName());
        $ids = $t->getAdapter()->fetchCol($sql);
        $group_ids = Zupal_Util_Array::random_set($ids, 4);

        $game_id = $this->view()->game->identity();

        foreach($group_ids as $id):
            $groups[] = $group = Ultimatum_Model_Ultgroups::getInstance()->get($id);

            $gamegroup = new Ultimatum_Model_Ultgamegroups();
            $gamegroup->game = $game_id;
            $gamegroup->group_id = $id;
            $gamegroup->save(); // note -- will be saved with no owner.
            $gamegroup->start_size();

            $scans[] = $this->view()->player->scan_group($group);
        endforeach;

        $this->view()->groups = $groups;
        $this->view()->scans = $scans;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ startexecuteAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     */

    public function execute () {
        $c = $this->get_controller();
        if (!$c->_prep()):
            return $this->forward('index', 'index', NULL, array('error' => 'Cannot prep'));
        endif;
        $this->view()->player->acquire($this->getParam('group'));
        $this->forward('run');
    }

}
