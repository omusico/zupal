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
            $pPendingOrder = Zupal_Domain_Abstract::_as($pPendingOrder, 'Ultimatum_Model_Ultplayergrouporder');
            $pg = $pPendingOrder->player_group();
            $ot = $pPendingOrder->order_type();
            $type = $ot->identity();
?>
<fieldset>
    <legend>
        <?= $pg ?>
        
    </legend>
 <? if (($type == 'resize') && $pPendingOrder->resize()): ?>
<?=  $this->view->powerMatrix($pPendingOrder->resize()) ?>
    <? endif; ?>
    <p><b><?= $ot ?></b>: <?= $ot->description ?>
    <? if ($pPendingOrder->repeat): ?>
    (Repeat</b> <?= $pPendingOrder->end_phrase() ?>)
<? endif; ?>
        <a href="/ultimatum/game/cancelorder/order/<?= $pPendingOrder->identity() ?>" class="linkbutton">Cancel Order</a>
</p>


</fieldset>
<?
        }
}
