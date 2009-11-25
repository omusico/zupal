<?

class Ultimatum_Form_Groupsizechange
extends Zupal_Fastform_Form
{

    public function __construct($pGroup, $pGame = NULL) {

        $pGroup = Zupal_Domain_Abstract::_as($pGroup, 'Ultimatum_Model_Ultgroups');

        if ($pGame):
            $pGame = Zupal_Domain_Abstract::_as($pGame, 'Ultimatum_Model_Ultgames');
        else:
            $pGame = Ultimatum_Model_Ultgames::get_active();
        endif;

        parent::_load();

        $this->game_id->set_value($pGame->identity());

        $this->group_id->set_value($pGroup->identity());

        $this->set_label('Change size of ' . $pGroup);
    }
/**
 * overload to create config based
 * @return string;
 */
    protected function _ini_path(){
        return preg_replace('~php$~', 'ini', __FILE__);
     }

}

