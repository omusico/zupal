<?

class Synerg_Form_Resourcetypessm
extends Synerg_Form_Resourcetypes {

    public function _init () {
        parent::_init();
        $this->set_template('Synerg_Form_Gameresoucetypessmtemplate');
        $this->set_action('/admin/synerg/resourcetypes')
    }

}