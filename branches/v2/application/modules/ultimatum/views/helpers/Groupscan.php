<?

class Ultimatum_View_Helper_Groupscan
extends Zend_View_Helper_Abstract
{

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ groupscan @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param Ultimatum_Model_Ultplayergroupknowledge $pScan
 * @return string
 */
    public function groupscan (Ultimatum_Model_Ultplayergroupknowledge $pScan, $pContent = NULL) {

        $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/style/ultimatum/ult_style.css');
        $group = $pScan->get_group();

        ob_start();
        ?>
<fieldset>
    <legend>(ID <?= $group->identity() ?>)
        <?= $group->get_title() ?>
        (as of turn <?= $pScan->get_game()->turn(TRUE) ?>)</legend>
     <?= $this->view->powerMatrix($pScan) ?>

    <p>
    <b><?= $group->get_lead() ?></b>
        <?= $group->get_content() ?>
    </p>
<? if(($orders = $pScan->pending_orders())): ?>
    <hr />
    <h3>Pending Orders</h3>
    <ol>
<? foreach($orders as $order): ?>
        <li>
            <?= $order ?> <?= $order->cancel_link() ?>
        </li>
<? endforeach; ?>
    </ol>
<? endif; ?>

    <hr />
    <?= $pContent; ?>
</fieldset>
        <?
        return ob_get_clean();
    }
}