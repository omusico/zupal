<?php

class Zupal_Helper_Zupalticket
extends Zend_View_Helper_Abstract
{

    public function zupalticket($pValue, $pTitle, $pButtons = NULL) {
        if (is_array($pButtons)):
            $pButtons = $this->view->navigation($pButtons);
        endif;

        if (is_object($pValue)):
            if (!$pTitle):
                $pTitle = (string)$pValue;
            endif;
            $pValue = $pValue->toArray();

        endif;
        ob_start();
        ?>
<fieldset class="ticket">
    <legend><?= $pTitle ?></legend>
    <dl>
        <? foreach($pValue as $title => $value): ?>
        <dt><?= $title ?></dt>
        <dd><?= $value ?></dd>
        <? endforeach; ?>
    </dl>
        <? if ($pButtons): ?>
    <br clear="all" />
<? foreach($pButtons as $button):
    $href = $button->getHref();
    echo $this->view->zupallinkbutton($href, $button->getLabel());
endforeach; ?>
        <? endif; ?>
</fieldset>
        <?

        return ob_get_clean();
    }
}