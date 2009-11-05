<?
class Ultimatum_View_Helper_Networknode
extends Zend_View_Helper_Abstract
{
    public $view;

    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ networknode @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param $pPlayer_group
 * @return string
 */
    public function networknode ($pPlayer_group) {
        $group = $pPlayer_group->get_group();
        ob_start();
        ?>

<fieldset>
    <legend>Group <?= $group->get_title() ?></legend>
    <p><b><?= $group->get_lead() ?></b></p>
    <blockquote>
        <?= $group->get_content(); ?>
    </blockquote>
    <ul>
        <li>
            <a href="<?= $this->view->baseUrl() ?>/ultimatum/game/network/group/<?= $group->identity() ?>">
                Investigate Other Groups
            </a>
        </li>
        <li>
            <a href="<?= $this->view->baseUrl() ?>/ultimatum/game/offensse/group/<?= $group->identity() ?>">
                Interact with Other Group
            </a>
        </li>
        <li>
            <a href="<?= $this->view->baseUrl() ?>/ultimatum/game/offensse/transfer/<?= $group->identity() ?>">
                Transfer Resources
            </a>
        </li>
        <li>
            <a href="<?= $this->view->baseUrl() ?>/ultimatum/game/offensse/move/<?= $group->identity() ?>">
                Reorganize
            </a>
        </li>
    </ul>
</fieldset>
<?
    return ob_get_clean();
    }

}