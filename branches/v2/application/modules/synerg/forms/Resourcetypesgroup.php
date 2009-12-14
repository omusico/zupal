<?

class Synerg_Form_Resourcetypesgroup
extends Synerg_Form_Resourcetypes {

    public function _init () {
        parent::_init();
        $this->set_template('Synerg_Form_Gameresoucetypesgrouptemplate');
        $this->set_action('/admin/synerg/resourcesresponseedit');
    }

}