<?

class Game_Admin_SessionsAction
extends Zupal_Controller_Action_CrudAbstract {
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ list @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function store() {
        $data = $this->_model(TRUE)->find(array('active' => 1), 'created_date DESC');
        foreach($data as $key => $value):
            $data[$key] = $value->toArray(TRUE);
        endforeach;

        $this->get_controller()->_store('id', $data, 'title');
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ responses @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * handle a created/updated form
     */
    public function responseedit () {

        $form = $this->_form(TRUE);
        $domain = $form->get_domain();


        $dclass = get_class($domain);

        if ($form->isValid()):
            $form->save();
            $params = array(
                'message' => 'Game session ' . $domain->session_title . ' saved',
                'id' => $domain->identity()
            );
            return $this->forward('sessionsviewitem', NULL, NULL, $params);
        else:
            $params = $this->getAllParams();
            $params['reload'] = TRUE;
            $params['error'] = 'Cannot save ' . $domain->title;
            $action = $domain->isSaved() ? 'sessionsedititem' : 'sessionsnewitem';

            return $this->forward($action, NULL, NULL, $params);
    endif;

    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ delete session @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * delete a record
     */
    public function responsedelete () {
        $model = $this->_model(FALSE);

        if ($model && $model->isSaved()):
            $model->delete();
            $params = array('message' => 'deleted ' . $model->session_title);
            return $this->forward('sessionsitems', NULL, NULL, $params);
        else:
            return $this->error('Mo Session to delete');
    endif;

    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ class resource names @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * returns the name of the model class, as a string
     */
    protected function _model_class() { return 'Game_Model_Gamesessions'; }

    /**
     * returns the name of the form class as a a string;
     */
    protected function _form_class() { return 'Game_Form_Gamesessions'; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ error @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pMessage
     * @param string $pAction
     */
    function error ($pMessage, $pAction = NULL) {
        throw new Exception(__METHOD__ . ': not implemented');
    }

    /**
     * the name of this action.
     * @return string;
     */
    public function prefix() {
        return 'sessions';
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ viewitem @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function viewitem () {
        parent::viewitem();
        $this->_item_buttons();
        $this->helper()->actionStack($this->prefix() . 'itembuttons');
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ deleteitem @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function deleteitem () {

        $model = $this->_model(FALSE);
        if (!$model->isSaved()):
            return $this->error('Attempt to delete non-existing session');
        endif;
        $this->helper()->actionStack('sessionsviewitem');

        $options = array(
            'Yes' => '/admin/game/sessionsresponsedelete/id/' . $model->identity(),
            'No' => '/admin/game/sessionsitems/message/Cancelled%20Deletion'
        );

        $params = array(
            'question' => 'Are you sure you want to delete this session?',
            'options' => $options
        );
        $this->helper()->actionStack('dialog', 'resources', 'administer', $params);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ edititem @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function edititem () {
        parent::edititem();
        $this->helper()->actionStack($this->prefix() . 'itembuttons');
    }


    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _item_buttons @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * puts the item button menu into the view context
     */
    protected function _item_buttons () {
        $module = Administer_Model_Modules::getInstance()->get('game');
        $buttons = $module->config_node('session_admin_menu');

        $pages = new Zend_Navigation($buttons);
        foreach($pages as $key => $button):
            $button->setParams(array('id' => $this->view()->model->identity()));
        endforeach;

        $this->view('buttons', $pages);
        return $pages;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ itembuttons @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function itembuttons () {
        $this->_item_buttons();
    }

}