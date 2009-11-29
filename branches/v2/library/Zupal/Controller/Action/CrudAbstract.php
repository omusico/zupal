<?

abstract class Zupal_Action_CrudAbstract
extends Zupal_Controller_Action_Abstract {
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ list @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 * note most of the "view/form" methods are NOT abstract --
 * all the functionality is expected to exist in the view.
 * their RESPONSE ia not though.
 */
    public function run() {
        $this->_model();
    }

    public function items() {
    }

    abstract public function store();

    public function newitem() {
        $this->_form();
    }

    abstract public function newresponse ();

    public function edititem() {
        $this->_form();
    }

    abstract public function editresponse();

    /**
     * note in many cases this won't actually be useful --
     * a one click delete should hop to deleteresponse.
     */
    function deleteitem() {
    }

    abstract public function deleteresponse();

    protected function _form() {
        $fc = $this->_form_class();
        $this->view('form', new $fc($this->_model()));
    }

/**
 *
 * @return Zupal_Domain_Abstract
 */
    protected function _model() {
        if ($id = $this->get_controller()->getParam('id')):
            $dc = $this->_model_class();
            $model = new $dc($id);
        else:
            $model = NULL;
        endif;
        $this->view('model', $model);
        return $model;
    }

    /**
     * returns the name of the model class, as a string
     */
    abstract protected function _model_class();

    /**
     * returns the name of the form class as a a string;
     */
    abstract protected function _form_class();

}