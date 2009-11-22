<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Hidden
 *
 * @author bingomanatee
 */
class Zupal_Fastform_Field_Hidden
extends Zupal_Fastform_Field_Abstract
{

    private $_tag_name = 'hidden'; // a flag for xdebug. 
    public function tag_name (){ return 'hidden'; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ hidden @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return boolean
     */
    public function display_props () {
        return array('show_label' => FALSE, 'show_field' => TRUE, 'hidden' => TRUE);
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __toString @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function __toString() {
        return sprintf('<input type="hidden" id="%s" name="%s" value="%s" />',
            $this->get_id(), $this->get_name(), $this->get_value());
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ express @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function express () {
        return sprintf('<input type="hidden" id="%s" name="%s" value="<?= $%s ?>" />',
            $this->get_id(), $this->get_name(), $this->get_name());
    }
}
