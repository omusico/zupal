<?

class Zupal_Helper_Zupalicon
extends Zend_View_Helper_Abstract {

    public function zupalicon($type, $link, $label = '') {
        if ($link instanceof Zend_Navigation_Page):
            if(!$label):
                $label = $link->getLabel();
            endif;
            $link = $link->getHref();
        endif;

        return sprintf('<a href="%s" class="icon"><img src="%s/images/icons/%s.gif" />%s</a>',
            $link, $this->view->baseUrl(), $type, $label);
    }


}