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

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ my_props @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function my_props () {
        $props = parent::my_props();
        if (($rows = $this->get_rows()) > 1):
            $props['rows'] = $rows;
            unset($props['value']);
        endif;
        return $props;
    }

    public function __toString() {
        ob_start();
        $props = $this->render_props();

        if ($this->get_rows() == 1):
            $type = $this->get_prop('password') ? 'password' : 'text';
            printf('<input type="%s" %s />', $type, $props);
        else:
            $value = $this->get_value();
            printf('<textarea %s>%s</textarea>', $props, $value);
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

}
