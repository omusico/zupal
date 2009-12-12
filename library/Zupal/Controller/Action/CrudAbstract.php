<?

abstract class Zupal_Controller_Action_CrudAbstract
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

    public function newitem() {
        $this->_form();
    }

    public function edititem() {
        $this->_form();
    }

    public function viewitem() {
        $model = $this->_model(FALSE);
        if ((!$model) || (!$model->isSaved())):
            $this->error('Attempt to view non-existent item', $this->prefix() . 'items');
        endif;
    }


    /**
     * note in many cases this won't actually be useful --
     * a one click delete should hop to deleteresponse.
     */
    function deleteitem() {
    }
/**
 *
 * @param boolesn $pReload
 * @return Zupal_Fastform_Domainform
 */
    protected function _form($pReload = TRUE) {
        $fc = $this->_form_class();
        $form = new $fc($this->_model());
        $pReload = $this->getParam('reload', $pReload);
        if ($pReload):
            $form->load_field_values($this->getAllParams());
            $form->isValid();
        endif;
        $this->view('form', $form);
        return $form;
    }

    /**
     *
     * @return Zupal_Domain_Abstract
     */
    protected function _model($pSpawn_New = FALSE) {
        $dc = $this->_model_class();
        if ($id = $this->getParam('id')):
            $model = new $dc($id);
        elseif ($pSpawn_New):
            $model = new $dc();
        else:
            $model = NULL;
        endif;
        $this->view('model', $model);
        return $model;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ store @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * return a JSON Dojo data block
     */
    abstract public function store();


    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ responses @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 * handle a created/updated form
 */
    abstract public function responseedit ();
/**
 * delete a record
 */
    abstract public function responsedelete ();


    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ class resource names @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * returns the name of the model class, as a string
     */
    abstract protected function _model_class();

    /**
     * returns the name of the form class as a a string;
     */
    abstract protected function _form_class();

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ error @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pMessage
     * @param string $pAction
     */
    abstract public function error ($pMessage, $pAction = NULL);

    /**
     * the name of this action.
     * @return string;
     */
    abstract public function prefix();

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ moveitem @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * not all objects are sortable -- but for those what are this is the extension point of rresorting. 
     */
    public function moveitem () {
    }
}