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

    public function newresponse () {

    }

    public function editresponse() {

    }

    public function deleteitem() {

    }

    public function deleteresponse() {

    }

    protected function _model_class() { return 'Game_Model_Gametypes'; }
    
    protected function _form_class() { return 'Game_Form_Gametypes'; }

}