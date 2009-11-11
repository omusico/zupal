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
    public function networknode ($pPlayer_group) {

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
    <ul>
        <li>
            <a class="actionbutton" href="<?= $this->view->baseUrl() ?>/ultimatum/game/network/group/<?= $group->identity() ?>">
                Investigate Other Groups
            </a>
        </li>
        <li>
            <a class="actionbutton"  href="<?= $this->view->baseUrl() ?>/ultimatum/game/interact/group/<?= $group->identity() ?>">
                Interact with Other Group
            </a>
        </li>
        <li>
            <a class="actionbutton"  href="<?= $this->view->baseUrl() ?>/ultimatum/game/resize/group/<?= $group->identity() ?>">
                Adjust Group Size
            </a>
        </li>
        <li>
            <a class="actionbutton"  href="<?= $this->view->baseUrl() ?>/ultimatum/game/move/group<?= $group->identity() ?>">
                Reorganize
            </a>
        </li>
        <li>
            <a class="actionbutton"  href="<?= $this->view->baseUrl() ?>/ultimatum/game/rebrand/group/<?= $group->identity() ?>">
                Re-brand
            </a>
        </li>
    </ul>
</fieldset>
<?
    return ob_get_clean();
    }

}