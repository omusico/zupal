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
            $this->set_label('Edit Order Type &quot;' . $this->get_domain()->get_title() . '&quot;');
            $this->name->set_prop('readonly', 'readonly');
            $this->title->set_value($this->get_domain()->get_title());
            $this->lead->set_value($this->get_domain()->get_lead());
            $this->content->set_value($this->get_domain()->get_content());
        else:
            $this->set_label('Create Order Type');
        endif;

        $this->set_field_width(300);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_field_values @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pFields
     * @return void
     */
    public function load_field_values ($pFields) {
        parent::load_field_values($pFields);
        if (array_key_exists('title', $pFields)):
            $this->get_domain()->set_title($pFields['title']);
        endif;

        if (array_key_exists('lead', $pFields)):
            $this->get_domain()->set_lead($pFields['lead']);
        endif;

        if (array_key_exists('content', $pFields)):
            $this->get_domain()->set_content($pFields['content']);
        endif;
    }
    
}

