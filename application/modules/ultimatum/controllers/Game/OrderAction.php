<?

class Ultimatum_Action_OrderAction
extends Zupal_Controller_Action_Abstract {

    public function run() {
        $c = $this->get_controller();
        if (!$c->_prep()):
            return $this->forward('index', 'index', NULL, array('error' => 'problem loading game'));
        elseif(!$this->view()->player_group):
            $params = array('error' => 'Cannot load group');
            return $this->_forward('run', NULL, NULL, $params);
        endif;
        $this->_draw_network();
        $order = $this->_getParam("order",  NULL );
        $this->view()->order_type = Ultimatum_Model_Ultplayergroupordertypes::getInstance()->get($order);
    }


    public function execute() {
        if (!$c->_prep()):
            return $this->forward('index', 'index', NULL, array('error' => 'problem loading game'));
        endif;
        $form = new Ultimatum_Form_Ultplayergrouporder($this->_getParam('player_group'));
        $params = $this->_getAllParams();
        $params['commander'] = $this->view()->player->identity();
        if ($form->isValid($params)):
            $form->save();
            $data = $form->get_domain()->toArray();
            error_log(__METHOD__ . ': saving order ' . print_r($data, 1) . ' from params ' . print_r($params, 1));
            $params = array('message' => 'Order Given');
            return $this->_forward('run', NULL, NULL, $params);
        else:
            $params = array('error' => 'Cannot give order');
            return $this->_forward('order', NULL, NULL, $params);
    endif;
    }

}
