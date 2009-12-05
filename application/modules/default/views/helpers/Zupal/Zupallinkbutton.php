<?

class Zupal_Helper_Zupallinkbutton extends Zend_View_Helper_Abstract {

    public function zupallinkbutton($link, $label, $props = '') {
        if ($link instanceof Zend_Navigation_Page):
            if(!$label):
                $label = $link->getLabel();
            endif;
            $link = $link->getHref();
        endif;

        if (is_array($props)):
            $props = $this->_props($props);
        endif;

        return sprintf('<a href="%s" class="linkbutton" %s>%s</a>', $link, $props, $label);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _props @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $rops
     * @return <type>
     */
    public function _props ($rops) {
        $prop_string = '';

        foreach($props as $prop => $value):
            $prop_string .= sprintf( ' %s="%s" ', $prop, addcslashes($value, '"'));
        endforeach;
        return $prop_string;
    }

}