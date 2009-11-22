<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ultplayergroupordertypes
 *
 * @author bingomanatee
 */
class Ultimatum_Form_Ultplayergroupordertypes
extends Zupal_Fastform_Domainform {

    protected function _domain_class() { return 'Ultimatum_Model_Ultplayergroupordertypes'; }

    protected function _ini_path() { return preg_replace('~php$~', 'ini', __FILE__); }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    protected function _init () {
        if ($this->get_domain()->isSaved()):
            $this->set_label('Edit Order Type &quot;' . $this->get_domain()->title . '&quot;');
            $this->name->set_prop('readonly', 'readonly');
        else:
            $this->set_label('Create Order Type');
        endif;

        $this->set_field_width(300);
    }

}

