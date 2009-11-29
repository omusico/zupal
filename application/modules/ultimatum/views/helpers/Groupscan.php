<?

class Ultimatum_View_Helper_Groupscan
extends Zend_View_Helper_Abstract
{

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ groupscan @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param Ultimatum_Model_Ultplayergroupknowledge $group
 * @return string
 */
    public function groupscan (Ultimatum_Model_Ultplayergroupknowledge $group, $pContent = NULL) {

        $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/style/ultimatum/ult_style.css');

        ob_start();
        ?>
<fieldset>
    <legend>(ID <?= $group->identity() ?>)
        <?= $group->get_title() ?>
        (as of turn <?= Ultimatum_Model_Ultgames::get_active()->turn(TRUE) ?>)</legend>
     <?= $this->view->powerMatrix($group) ?>

    <p>
    <b><?= $group->get_lead() ?></b>
        <?= $group->get_content() ?>
<?= $this->view->zupallinkbutton('/ultimatum/game/interact/target/' . $group->identity() . '/game/' . Ultimatum_Model_Ultgames::get_active_id(), 'Interact') ?>
    </p>
<? if(($orders = $group->pending_orders())): ?>
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