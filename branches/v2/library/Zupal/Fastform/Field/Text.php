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
//put your code here
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __construct @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param string $pName
 * @param string $pValue
 * @param Zupal_Fastform_Abstract $pForm
 * @param array $pParams
 * @return void
 */
    public function __construct ($pName, $pLabel, $pValue, $pProps = array(), $pForm = NULL) {
        parent::__construct($pName, $pLabel, $pValue, $pProps, $pForm);
        if ($this->get_form()):
            $this->get_form()->set_field($this);
        endif;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string
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
            printf('<input type="text" %s />', $props);
        else:
            printf('<textarea %s>%s</textarea>', $props, $this->get_value());
        endif;

        $out = ob_get_clean();
        return $out;
    }

}
