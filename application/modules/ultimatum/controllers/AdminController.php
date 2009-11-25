<?php
class Ultimatum_AdminController extends Zupal_Controller_Abstract {
/**
 *
 */

    public function preDispatch() {
        $u = Model_Users::current_user();
        if (!$u || ! $u->can('ultimatum_manage')):
            $param = array('error' => 'This area is reserved for administrators');
            return $this->_forward('insecure', 'error', 'administer', $param);
        endif;
        $this->_helper->layout->setLayout('admin');
        parent::preDispatch();
    }

    public function indexAction() {
    }

    public function gamesAction() {
    }

    /**
     *
     */

    public function gamesstoreAction() {
        $pt = Ultimatum_Model_Ultgames::getInstance();
        $games = $pt->find(array('status' => array('deleted', '!=')), 'id');
        $data = array();
        foreach($games as $game):
            $row = $game->toArray();
            $players = $game->players(TRUE);
            $turn = $game->turn(TRUE);
            $row['players'] = count($players);
            $row['turn'] = $turn;
            $data[] = $row;
        endforeach;
        ksort($data);
        $this->_store('id', $data, 'name');
    }

    public function groupsAction() {
    }

    /**
     *
     */

    public function groupsstoreAction() {
        $pt = Ultimatum_Model_Ultgroups::getInstance();
        $groups = $pt->findAll('id');
        $data = array();
        foreach($groups as $group):
            $name = $group->get_title();
            $row = $group->toArray();
            $row['title'] = $name;
            $data[] = $row;
        endforeach;
        ksort($data);
        $this->_store('id', $data, 'name');
    }

    public function groupeditAction() {
        $id = $this->_getParam("id",  NULL );
        $this->view->group = Ultimatum_Model_Ultgroups::getInstance()->get($id);
        $this->view->form = new Ultimatum_Form_Ultgroups($this->view->group);
        if ($this->_getParam('reload')):
            $this->view->form->isValid($this->_getAllParams());
    endif;
    }

    /**
     *
     */

    public function groupnewAction() {
        $this->_forward('groupedit');
    }

    /**
     *
     */

    public function groupeditexecuteAction() {
        $form = new Ultimatum_Form_Ultgroups($this->_getParam('id'));
        if ($form->isValid($this->_getAllParams())):
            $form->save();
        else:
            $params = $this->_getAllParams();
            $params['reload'] = TRUE;
            $params['error'] = 'cannot save page';
            return $this->_forward('edit', NULL, NULL, $params);
        endif;
        $this->_forward('groupview', 'admin', NULL, array('id' =>  $form->get_domain()->identity()));
    }

    public function groupviewAction() {
        $id = $this->_getParam("id",  NULL );
        $this->view->group = Ultimatum_Model_Ultgroups::getInstance()->get($id);
    }

    public function groupsrandAction() {
        $count = $this->_getParam("count",  NULL );
        $randtype = $this->_getParam("randtype",  NULL );
        switch($randtype):
            case 'dist':
            case 'pure':
                for ($i = 0; $i < $count; ++$i):
                    $group = new Ultimatum_Model_Ultgroups();
                    $group->randomize();
                    $group->save();
                endfor;
                break;
        endswitch;
        $params = array('message' => $count . ' groups made');
        $this->_forward('groups', NULL, NULL, $params);
    }

    public function groupsdeleteAction() {
        $indexes = $this->_getParam("grid_indexes",  NULL );
        foreach(explode(',', $indexes) as $index) {
            $group = Ultimatum_Model_Ultgroups::getInstance()->get($index);
            if ($group->isSaved()):
                $group->delete();
        endif;
        }

        $parmas = array('message' => 'Deleted ' . $indexes);
        $this->_forward('groups', NULL, NULL, $params);
    }

    public function gamesdeleteAction() {
        $grid_indexes = $this->_getParam("grid_indexes",  NULL );
        $gi = Ultimatum_Model_Ultgames::getInstance();
        foreach(split(',', $grid_indexes) as $id):
            $game = $gi->get($id);
            if ($game->isSaved()):
                $game->delete();
        endif;
        endforeach;
        $parmas = array('message' => 'Deleted ' . $grid_indexes);
        $this->_forward('games', NULL, NULL, $params);
    }

    public function ordertypesAction() {
    }

    public function ordertypeeditAction() {
        $id = $this->_getParam("id",  NULL );
        $this->view->ordertype = Ultimatum_Model_Ultplayergroupordertypes::getInstance()->get($id);
        $this->view->form = new Ultimatum_Form_Ultplayergroupordertypes($this->view->ordertype);
    }

    /**
     *
     */

    public function ordertypeactivateAction() {
        $this->_change_ota(TRUE);
    }

    /**
     *
     */

    public function ordertypestoreAction() {
        $ots = Ultimatum_Model_Ultplayergroupordertypes::getInstance()->findAll('name');
        $out = array();
        foreach($ots as $ot):
            $data = $ot->toArray();
            $data['title'] = $ot->get_title();
            $data['content'] = $ot->get_content();
            $out[] = $data;
        endforeach;
        $this->_store('name', $out, 'title');
    }

    /**
     *
     */

    public function ordertypedeactivateAction() {
        $this->_change_ota(FALSE);
    }

    /**
     *
     */

    public function ordertypeeditexecuteAction() {
        $name = $this->_getParam('name');
        $form =  new Ultimatum_Form_Ultplayergroupordertypes($name);
        $gap = $this->_getAllParams();
        $form->load_field_values($gap);
        if ($form->isValid()):
            $form->save();
            $params = array('message' => $name . ' Updated');
            $this->_forward('ordertypes', NULL, NULL, $params);
        else:
            $message = 'problems saving ' . $name;
            $params = array('error' => $message);
            $this->_forward('ordertyeedit', NULL, NULL, $params);
    endif;
    }

    /**
     * @param boolean $pChange_to
     * @return NULL
     *
     *
     */

    public function _change_ota($pChange_to) {
        $ot = Ultimatum_Model_Ultplayergroupordertypes::getInstance()->get($name = $this->_getParam('name'));
        if ($ot->isSaved()):
            $ot->active = $pChange_to ? 1 : 0;
            $ot->save();
            $params = array('message' => $ot->title . ' active set to ' . ($pChange_to ? 'TRUE' : 'FALSE'));
        else:
            $params = array('error' => "no action $name found");
        endif;
        $this->_forward('ordertypes', NULL, NULL, $params);
    }

    public function ordertypenewAction() {
    }

    public function gamesviewAction() {
        $id = $this->_getParam("id",  NULL );
        $this->view->game = Ultimatum_Model_Ultgames::getInstance()->get($id);
    }

    /**
     *
     */

    public function gameviewstoreAction() {
        $game_id = $this->_getParam('game');
        $game = Ultimatum_Model_Ultgames::getInstance()->get($game_id);
        $player_ids = $game->player_ids();
        $player_group_ids = array_values(Ultimatum_Model_Ultplayergroups::getInstance()->player_group_ids($player_ids, TRUE));
        $scanned_group_ids = array_keys(
            Ultimatum_Model_Ultplayergroupknowledge::getInstance()->last_scans_for_player($player_ids, TRUE)
        );
        $group_ids = array_merge($player_group_ids, $scanned_group_ids);
        array_unique($group_ids);
        $find_params = array('id' => array($group_ids, 'in'));
        $groups = Ultimatum_Model_Ultgroups::getInstance()->find($find_params);
        $out = array();
        foreach($groups as $group):
            $data = $group->toArray();
            $data['size'] = $group->size_in_game($game_id);
            $data['title'] = $group->get_title();
            $out[] = $data;
        endforeach;
        $this->_store('id', $out);
    }

    public function gamegroupviewAction() {
        $id = $this->_getParam("id",  NULL );
        $game_id = $this->_getParam('game');
        $this->view->group = $group = Ultimatum_Model_Ultgroups::getInstance()->get($id);
        $this->view->game = $game = Ultimatum_Model_Ultgames::getInstance()->get($game_id);
        $game->activate();
    }

    public function groupresizeAction() {
        $game_id = $this->_getParam("game_id",  NULL );
        $group_id = $this->_getParam("group_id",  NULL );
        $size = (int) $this->_getParam('size');
        $mode = $this->_getParam('mode');

        $game  = Ultimatum_Model_Ultgames::getInstance()->get($game_id);
        $group = Ultimatum_Model_Ultgroups::getInstance()->get($group_id);

        $sizer = new Ultimatum_Model_Ultplayergroupsize();
        $sizer->group_id = $group_id;
        $sizer->game = $game_id;
        $sizer->turn = $game->turn();

        switch($mode):
            case 'relative':
                if ($size):
                    $sizer->size = $size;
                    $sizer->save();
                    $m_key = 'message';
                    $message = 'Sice changed by ' . $size;
                else:
                    $m_key = 'error';
                    $message = 'No Size Change Made';
                endif;
                break;

            case 'absolute':
                $current = $group->size_in_game($game_id);
                if ($current != $size):
                    $size -= $current;
                    $sizer->size = $size;
                    $sizer->save();
                    $m_key = 'message';
                    $message = 'Size changed to ' . $size;
                else:
                    $m_key = 'error';
                    $message = 'No Size Change Made';                        
                endif;
                break;
        endswitch;

        $params = array('id' => $group_id, 'game' => $game_id, $m_key => $message);

        $this->_forward('gamegroupview', NULL, NULL, $params);
    }

}

