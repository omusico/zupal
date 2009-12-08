<?

class Synerg_Game_SessionAction
extends Zupal_Controller_Action_CrudAbstract
{

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ store @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * return a JSON Dojo data block
     */
     public function store(){

    }


    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ responses @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 * handle a created/updated form
 */
     public function responseedit (){
        $form = $this->_form();
        if (!$form->isValid()):
            $params = array('error' => 'Your session cannot be created', 'reload' => TRUE);
            $this->forward('index', 'game', NULL, $params);
        endif;
        $form->save();
        $domain = $form->get_domain();

        $params = array('session' => $domain->identity());
        
        $this->forward('start', 'play', NULL, $params);

    }
/**
 * delete a record
 */
     public function responsedelete (){
         throw new Exception(__METHOD__ . ': not implemented');
    }


    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ class resource names @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * returns the name of the model class, as a string
     */
     protected function _model_class(){

    }

    /**
     * returns the name of the form class as a a string;
     */
     protected function _form_class(){
        return 'Synerg_Form_Sessions';
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ error @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pMessage
     * @param string $pAction
     */
     public function error ($pMessage, $pAction = NULL){
        throw new Exception(__METHOD__ . ': not implemented');
    }

    /**
     * the name of this action.
     * @return string;
     */
     public function prefix(){
        return 'session';
    }
}