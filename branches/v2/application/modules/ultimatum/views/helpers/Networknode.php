<?
class Ultimatum_View_Helper_Networknode
extends Zend_View_Helper_Abstract
{
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ networknode @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param $pPlayer_group
 * @return string
 */
    public function networknode ($pPlayer_group, $pContent = NULL) {

        $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/style/ultimatum/ult_style.css');

        $group = $pPlayer_group->get_group();
        ob_start();
        ?>

<fieldset>
    <legend>Group <?= $group->get_title() ?></legend>
     <?= $this->view->powerMatrix($group) ?>
    <b><?= $group->get_lead() ?></b>
    <hr />
        <?= $group->get_content(); ?>
    <? if ($pContent):
    echo $pContent;
    else: ?>
    <ul>
    <?
    $params = array('active' => 1);
    foreach(Ultimatum_Model_Ultplayergroupordertypes::getInstance()->find($params) as $ot):
    ?>
        <li><a class="linkbutton" href="/ultimatum/game/order/group/<?= $group->identity() ?>/order/<?= $ot->identity() ?>/"><?= $ot ?></a>
            <?= $ot->description ?>
    <? endforeach; ?>
    </ul>
        <? endif; ?>
</fieldset>
<?
    return ob_get_clean();
    }

}