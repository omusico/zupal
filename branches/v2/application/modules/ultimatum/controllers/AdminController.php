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
    }

    public function indexAction() {
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

    public function groupsrandAction()
    {
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

    public function groupsdeleteAction()
    {
        $indexes = $this->_getParam("indexes",  NULL );
        foreach(explode(',', $indexes) as $index)
        {
            $group = Ultimatum_Model_Ultgroups::getInstance()->get($index);
            if ($group->isSaved()):
                $group->delete();
            endif;
        }
        $parmas = array('message' => 'Deleted ' . $indexes);
        $this->_forward('groups', NULL, NULL, $params);
    }

}

