<?php

/**
 * Description of Pendingorder
 *
 * @author bingomanatee
 */
class Ultimatum_View_Helper_Pendingorder
extends Zend_View_Helper_Abstract
{
    //put your code here

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ pendingorder @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
        public function pendingorder ($pPendingOrder) {
            $pPendingOrder = Zupal_Domain_Abstract::_as($pPendingOrder, 'Ultimatum_Model_Ultplayergrouporders');
            $pg = $pPendingOrder->player_group();
            $ot = $pPendingOrder->order_type();
            $type = $ot->identity();
            $target = $pPendingOrder->get_target();
?>
<fieldset>
    <legend>
        (ID: <?= $pPendingOrder->identity() ?>)
        <?= $pg ?>        
    </legend>
 <? if (($type == 'resize') && $pPendingOrder->resize()): ?>
<?=  $this->view->powerMatrix($pPendingOrder->resize()) ?>
    <? endif; ?>
    <p><b><?= $ot ?></b>
        <? if ($target): ?> to <?= $target ?> <? endif; ?>
        <?= $pPendingOrder->cancel_link() ?>
</p>


</fieldset>
<?
        }
}
