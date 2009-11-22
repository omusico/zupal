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
        return 'Ultimatum_Model_Ultplayergrouporder';
    }

    protected function _ini_path() {
        return preg_replace('~php$~', 'ini', __FILE__);
    }

}

