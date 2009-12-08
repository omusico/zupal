<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Button
 *
 * @author bingomanatee
 */
class Zupal_Fastform_Field_Button
extends Zupal_Fastform_Field_Abstract {

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_type @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function get_type () {
        if (($type = parent::get_type()) == 'submit'):
            return $type;
        else:
            return 'button';
        endif;
    }

    public function __toString() {
        $props = $this->render_props();

        if (($type = parent::get_type()) == 'submit'):
            $props .= ' class="submit" ';
        endif;

        $type=$this->get_type();
        ob_start();
        ?><input type="<?= $type ?>" <?= $props ?> /><?
        return ob_get_clean();
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ tag_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function tag_name () {
        return 'button';
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_value @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function get_value () {
        $value = parent::get_value();
        if (!$value) return $this->get_label();
        return $value;
    }
}
