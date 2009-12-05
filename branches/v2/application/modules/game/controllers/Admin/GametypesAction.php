<?

class Game_Admin_GametypesAction
extends Zupal_Controller_Action_CrudAbstract {
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ list @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function store() {
        $params = array('active' => 1);
        $items = $this->_model(TRUE)->find($params);

        $data = array();

        foreach($items as $item):
            $row = $item->toArray();
            $row['title'] = $item->get_title();
            $data[] = $row;
        endforeach;

        $this->get_controller()->_store('id', $data, 'title');
    }

    public function responseedit() {
        $form = $this->_form(TRUE);
        $domain = $form->get_domain();
        $dclass = get_class($domain);
        
        if ($form->isValid()):
            $form->save();
            $params = array(
                'message' => 'Gametype ' . $domain->title . ' saved', 
                'id' => $domain->identity()
            );
            return $this->forward('gametypesviewitem', NULL, NULL, $params);
        else:
            $params = $this->getAllParams();
            $params['reload'] = TRUE;
            $params['error'] = 'Cannot save ' . $domain->title;
            $action = $domain->isSaved() ? 'gametypesedititem' : 'gametypesnewitem';

            return $this->forward($action, NULL, NULL, $params);
        endif;


    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ viewitem @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function viewitem () {
        parent::viewitem();
        $this->_item_buttons();
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ edititem @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function edititem () {
        parent::edititem();
        $this->helper()->actionStack($this->prefix() . 'itembuttons');
    }

    public function deleteitem() {
        $this->view->gametype = $model = $this->_model(FALSE);
        if (!$model->isSaved()):
            return $this->error('Attempt to delete non-existing gametype');
        endif;
        $this->helper()->actionStack('gametypesviewitem');

        $options = array(
            'Yes' => '/admin/game/gametypesresponsedelete/id/' . $model->identity(),
            'No' => '/admin/game/gametypesitems/message/Cancelled%20Deletion'
        );

        $params = array(
            'question' => 'Are you sure you want to delete this game type? all games of this type will end. ',
            'options' => $options
        );
        $this->helper()->actionStack('dialog', 'resources', 'administer', $params);


    }

    public function responsedelete() {
        $model = $this->_model(FALSE);

        if ($model && $model->isSaved()):
            $model->delete();
            $params = array('message' => 'deleted ' . $model->title);
            return $this->forward('gametypesitems', NULL, NULL, $params);
        else:
            return $this->error('Mo Game Type to delete');
        endif;
    }

    protected function _model_class() { return 'Game_Model_Gametypes'; }
    
    protected function _form_class() { return 'Game_Form_Gametypes'; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ error @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pmessage
     * @return <type>
     */
    public function error ($pMessage, $pAction = NULL) {
        if (!$pAction) $pAction = 'gametypesitems';
        
        if (preg_match('~:(id|title)~', $pMessage)):
            $domain = $this->_model(FALSE);
            if ($domain):
                $needle = array(':id', ':title');
                $haystack = array($domain->identity(), $domain->title);
                $pMessage = str_replace($needle, $haystack, $pMessage);
            endif;
        endif;
        $params = array('error' => $pMessage);
        return $this->forward($pAction, NULL, NULL, $params);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ prefix @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return string
     */
    public function prefix () {
        return 'gametypes';
    }


    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _item_buttons @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * puts the item button menu into the view context
     */
    protected function _item_buttons () {
        $module = Administer_Model_Modules::getInstance()->get('game');
        $buttons = $module->config_node('game_type_admin_menu');

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