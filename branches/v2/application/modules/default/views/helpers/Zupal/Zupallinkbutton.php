<?

class Zupal_Helper_Zupallinkbutton extends Zend_View_Helper_Abstract {

    public function zupallinkbutton($link, $label) {
        return sprintf('<a href="%s" class="linkbutton">%s</a>', $link, $label);
    }

}