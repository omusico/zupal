<?php

class Ultimatum_Form_Ultplayergrouporder extends Zupal_Fastform_Domainform {


    public function __construct($pDomain = NULL, $pOrder_type = NULL, $pName = NULL,
        $pID = NULL,  $pAction = NULL, $pTargets = array()) {
        $pOrder_type = $pOrder_type ?
            Zupal_Domain_Abstract::_as($pOrder_type, 'Ultimatum_Model_Ultplayergroupordertypes', TRUE)
            : NULL;
        parent::__construct($pDomain, $pName, $pID, $pAction);
        $this->target->set_data_source($pTargets);
        $this->type->set_value($pOrder_type);
    }

    protected function _domain_class() {
        return 'Ultimatum_Model_Ultplayergrouporders';
    }

    protected function _ini_path() {
        return preg_replace('~php$~', 'ini', __FILE__);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ target @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function target () {
        if ($target = $this->target->get_value()):
            return Zupal_Domain_Abstract::_as($target, 'Ultimatum_Model_Ultplayergroups');
        else:
            return NULL;
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return void
     */
    public function save () {
        if (($this->mode->get_value() == 'replace')
            && ($target = $this->target())):
            Ultimatum_Model_Ultplayergrouporders::clear_orders($this->player_group->get_value(), $target);
        endif;
        return parent::save(); // magic delegates to the domain object. 
    }
    
}
