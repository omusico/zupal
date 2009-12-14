<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Text
 *
 * @author bingomanatee
 */
class Zupal_Fastform_Field_Text extends Zupal_Fastform_Field_Abstract {

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function tag_name () {
        return $this->get_rows() <= 1 ? 'text' : 'textarea';
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_prop @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pKey
     * @param string $pValue
     * @return void
     */
    public function set_prop ($pKey, $pValue) {
        switch($pKey):
            case 'align':
                return $this->set_align($pValue);
                break;

            default:
                return parent::set_prop($pKey, $pValue);
        endswitch;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ my_props @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function my_props () {
        $props = parent::my_props();
        if (($rows = $this->get_rows()) > 1): // text area.
            $props['rows'] = $rows;
            unset($props['value']);
        endif;

        return $props;
    }

    public function __toString() {
        ob_start();
        $props = $this->render_props();
        $style = $this->style_property();
        
        if ($this->get_rows() == 1):
            $type = $this->get_prop('password') ? 'password' : 'text';
            printf('<input type="%s" %s %s />', $type, $style, $props);
        else:
            $value = $this->get_value();
            printf('<textarea %s %s>%s</textarea>', $props, $style, $value);
        endif;

        $out = ob_get_clean();
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ express @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function express () {
        ob_start();
        $props = $this->express_props();

        if ($this->get_rows() == 1):
            $type = $this->get_prop('password') ? 'password' : 'text';
            printf('<input type="%s" %s />', $type, $props);
        else:
            $value = $this->express_value();
            printf('<textarea %s>%s</textarea>', $props, $value);
        endif;

        $out = ob_get_clean();
        return $out;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ align @@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * @return string;
     */

    public function get_align() { return $this->get_style('text_align'); }

    public function set_align($pValue) { $this->set_style('text-align', $pValue); }

    const ALGIN_RIGHT = 'right';
    const ALIGN_LEFT = 'left';
    const ALIGN_CENTER = 'center';
}
