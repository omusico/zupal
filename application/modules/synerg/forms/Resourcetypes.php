<?

class Synerg_Form_Resourcetypes
extends Game_Form_Gameresourcetypes
{
        public function _init () {
        parent::_init();
        $this->set_action('/admin/synerg/resourcesresponseedit');
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __toString @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    public function __toString () {
        if($this->get_domain()):
            $title = $this->get_domain()->resource_class()->title;
            switch (strtolower($title)):
                case 'resources':
                    $this->set_template('Synerg_Form_Gameresoucetypesresourcetemplate');
                    break;

                case 'group types':
                    $this->set_template('Synerg_Form_Gameresourcetypesgrouptypetemplate');
                    break;

                case 'groups':
                    $this->set_template('Synerg_Form_Gameresourcetypesgrouptemplate');
                    break;

                case 'social metrics':
                    $this->set_template('Synerg_Form_Gameresourcetypessmtemplate');
            endswitch;
        endif;
        return parent::__toString();
    }
}