<?php

class Ultimatum_Form_Ultplayergrouporder extends Zupal_Form_Abstract
{

    public function __construct($pGroup, $pType = NULL) {
        $ini_path = preg_replace('~php$~', 'ini', __FILE__);
        $config = new Zend_Config_Ini($ini_path, 'fields');
        parent::__construct($config);

        $this->_load_orders($pType);

        $pGroup = Zupal_Domain_Abstract::_as($pGroup, 'Ultimatum_Model_Ultplayergroups', TRUE);
        $pType = $pType ? Zupal_Domain_Abstract::_as($pType, 'Ultimatum_Model_Ultplayergroupordertypes', TRUE) : NULL;

        $this->player_group->setValue($pGroup);
        $this->type->setValue($pType);

        $this->id->removeDecorator('htmlTag');
        $this->id->removeDecorator('label');

        $this->start_turn->removeDecorator('htmlTag');
        $this->start_turn->removeDecorator('label');

        $this->player_group->removeDecorator('htmlTag');
        $this->player_group->removeDecorator('label');
    }

    public function domain_fields()
    {
        return array('player_group',"type","target",
            'repeat', "repeat_end");
    }

    protected function get_domain_class()
    {
        return "Ultimatum_Model_Ultplayergrouporder";
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _load_orders @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function _load_orders ($pType = NULL) {
        if ($pType):
            if (is_numeric($pType)):
                $pType = Zupal_Domain_Abstract::_as($pType, 'Ultimatum_Model_Ultplayergroupordertypes');
            elseif(is_string($pType)):
                $pType = Ultimatum_Model_Ultplayergroupordertypes::find_by_name($pType);
            else:
                $pType = Zupal_Domain_Abstract::_as($pType, 'Ultimatum_Model_Ultplayergroupordertypes');
            endif;
        endif;

        if ($pType):
            $this->type->setMultiOptions(array($pType->identity() => $pType->label()));
        else:
            foreach(Ultimatum_Model_Ultplayergroupordertypes::getInstance()->findAll() as $ot):
                $this->type->addMultiOption($ot->identity(), $ot->name);
            endforeach;
        endif;

        return $out;
    }
    
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ isValid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pParams
     * @return boolean
     */
    public function isValid ($pParams) {
        if (!$out = parent::isValid($pParams)):
            return $out;
        endif;

        $this->get_domain()->start_turn = Zend_Registry::get('ultimatum_game')->turn();
        switch($type):
            case 'iterate' :
                $this->repeat_end->setValue(max(1, (int) $this->repeat_end->getValue()));
            break;

            case 'turn':
                $max = (int) $this->get_domain_class()->get_game()->turn();
                $max = max(1, $max);
                $this->repeat_end->setValue($max);
            break;

            case 'forever':
            case 'once':
                // ignores end turn anyway -- no matter.
            break;
        endswitch;
        return TRUE;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function save () {
        parent::save();
    }
}

