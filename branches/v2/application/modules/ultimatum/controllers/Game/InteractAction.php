<?

class Ultimatum_Game_InteractAction
extends Zupal_Controller_Action_Abstract {

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ run @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 */
    public function run () {
        $c = $this->get_controller();

        if (!$c->_prep()):
            return $c->forward('index', 'index', NULL, array('error' => 'problem loading game'));
        elseif (!($target = $c->view->target)):
            $params = array('error' => 'Cannot find target');
            return $c->forward('run', NULL, NULL, $params);
        endif;

        $c->view->orders = Ultimatum_Model_Ultplayergroupordertypes::get_by_type(
            Ultimatum_Model_Ultplayergroupordertypes::TARGET_TYPE_OTHER,
            Ultimatum_Model_Ultplayergroupordertypes::TARGET_TYPE_BOTH );

        $c->_draw_network();
    }

}