<?

class Game_Admin_ResourceclassesAction
extends Zupal_Controller_Action_CrudAbstract {
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ list @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 */
    public function init () {
        if ($model = $this->_model()):
            $this->view('game_type', $model->game_type());
        endif;

        if ($game_type = $this->getParam('game_type')):
            $this->view('game_type', Game_Model_Gametypes::getInstance()->get($game_type));
        endif;
    }

    public function store() {
        $params = array('active' => 1);
        $items = $this->_model(TRUE)->find($params);

        $data = array();

        foreach($items as $item):
            $row = $item->toArray();
            $data[] = $row;
        endforeach;

        $this->get_controller()->_store('id', $data, 'title');
    }

    public function newitem() {
        parent::newitem();

        $form = $this->view()->form;

        $form->game_type->set_value($this->view()->game_type->identity());
    }

    public function responseedit() {
        $form = $this->_form(TRUE);
        $domain = $form->get_domain();
        $dclass = get_class($domain);

        if ($form->isValid()):
            $form->save();
            $params = array(
                'message' => 'Game Resource Class ' . $domain->title . ' saved',
                'id' => $domain->identity()
            );
            return $this->forward($this->prefix() . 'viewitem', NULL, NULL, $params);
        else:
            $params = $this->getAllParams();
            $params['reload'] = TRUE;
            $params['error'] = 'Cannot save ' . $domain->title;
            $action = $domain->isSaved() ? $this->prefix() . 'edititem' : $this->prefix() . 'newitem';

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
        $this->helper()->actionStack($this->prefix() . 'itembuttons');
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
        $this->helper()->actionStack($this->prefix() . 'viewitem');

        $options = array(
            'Yes' => '/admin/game/resourceclassesresponsedelete/id/' . $model->identity(),
            'No' => '/admin/game/resourceclassesitems/message/Cancelled%20Deletion'
        );

        $params = array(
            'question' => 'Are you sure you want to delete this resource class? all resources of this class will end. ',
            'options' => $options
        );
        $this->helper()->actionStack('dialog', 'resources', 'administer', $params);


    }

    public function responsedelete() {
        $model = $this->_model(FALSE);

        if ($model && $model->isSaved()):
            $model->delete();
            $params = array('message' => 'deleted ' . $model->title, 'id' => $model->game_type);
            return $this->forward('gametypesviewitem', NULL, NULL, $params);
        else:
            return $this->error('Mo Game Type to delete');
        endif;
    }

    protected function _model_class() { return 'Game_Model_Gameresourceclasses'; }

    protected function _form_class() { return 'Game_Form_Gameresourceclasses'; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ error @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pmessage
     * @return <type>
     */
    public function error ($pMessage, $pAction = NULL) {
        if (!$pAction) $pAction = $this->prefix() . 'items';

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
        return 'resourceclasses';
    }


    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _item_buttons @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * puts the item button menu into the view context
     */
    protected function _item_buttons () {
        $module = Administer_Model_Modules::getInstance()->get('game');
        $buttons = $module->config_node('game_type_resource_classes_menu');

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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ moveitem @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function moveitem () {
        if ($model = $this->_model()):
            $mode = $this->getParam('mode');
            $model->move($mode);
            $params = array(
                'id' => $model->game_type,
                'message' => $model . $mode
            );
            $this->forward('gametypesviewitem', NULL, NULL, $params);
        else:
            return $this->error(__METHOD__ .  ': No resouce class found to move');
        endif;
    }
}