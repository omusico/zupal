<?
class Ultimatum_View_Helper_Playergroup
extends Zend_View_Helper_Abstract
{
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ playergroup @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param $pPlayer_group
 * @return string
 */
    public function playergroup (Ultimatum_Model_Ultgamegroups $pPlayer_group, $pContent = NULL) {

        $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/style/ultimatum/ult_style.css');
        $po = $pPlayer_group->pending_orders();

        $group = $pPlayer_group->get_group();
        ob_start();
        ?>

<fieldset class="ult_groupinfo">
    <legend>Group <?= $group->get_title() ?></legend>
     <?= $this->view->powerMatrix($group) ?>
    <p><b><?= $group->get_lead() ?></b>:
        <?= $group->get_content(); ?></p>
    <? if ($pContent):
    echo $pContent;
    else: ?>
    <? if ($po && count($po)): ?>
    <h3>Pending Orders</h3>
    <ol>
        <? foreach($po as $o): ?>
        <li><?= $o ?> <?=$o->cancel_link() ?></li>
        <? endforeach; ?>
    </ol>

    <? endif; ?>
        <h3>Command Group</h3>
    <ul>
    <?
    $params = array('active' => 1);
    foreach(Ultimatum_Model_Ultplayergroupordertypes::getInstance()->find($params) as $ot):
    ?>
        <li><a class="linkbutton" href="/ultimatum/game/order/group/<?= $group->identity() ?>/order/<?= $ot->identity() ?>/"><?= $ot ?></a>
            <?= $ot->get_lead() ?>
    <? endforeach; ?>
    </ul>
        <? endif; ?>
</fieldset>
<?
    return ob_get_clean();
    }

}