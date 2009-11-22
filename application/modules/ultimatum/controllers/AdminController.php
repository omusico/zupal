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
        $games = $pt->findAll('id');
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
            $row['name'] = $name;
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ ordertypeactivateAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     */

    public function ordertypeactivateAction () {
        $this->_change_ota(TRUE);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ ordertypeactivateAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     */

    public function ordertypedeactivateAction () {
        $this->_change_ota(FALSE);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ ordertypeeditexecuteAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     */

    public function ordertypeeditexecuteAction () {
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _change_ota @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     * @param boolean $pChange_to
     * @return NULL
     */

    public function _change_ota ($pChange_to) {
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

    public function ordertypenewAction()
    {
    }

}

