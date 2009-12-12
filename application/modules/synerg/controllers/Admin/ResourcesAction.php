<?

class Synerg_Admin_ResourcesAction
extends Zupal_Controller_Action_CrudAbstract
{

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ store @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * return a JSON Dojo data block
     */
     public function store(){
        $gt = Synerg_Model_Gametypes::getInstance()->synergy_gametype();
        $out = array();
        $classes = Synerg_Model_Gameresourceclasses::getInstance()->find(array('active' => 1, 'game_type' => $gt->identity()));
        foreach($classes as $class):
            $cn = $class->title;
            foreach ($class->resource_types() as $resource):
                $data = $resource->toArray(TRUE);
                $data['resource_class_name'] = $cn;
                $out[] = $data;
            endforeach;
        endforeach;

        $this->get_controller()->_store('id', $out);
    }


    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ responses @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 * handle a created/updated form
 */
     public function responseedit (){
        throw new Exception(__METHOD__ . ': not impelemented');

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
        return 'Synerg_Model_rsources';
    }

    /**
     * returns the name of the form class as a a string;
     */
     protected function _form_class(){
        return 'Synerg_Form_Resources';
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


    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ prefix @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * the name of this action.
     * @return string;
     */
     public function prefix(){
        return 'resources';
    }


}