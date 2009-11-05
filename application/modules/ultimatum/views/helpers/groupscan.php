<?

class Ultimatum_View_Helper_Groupscan
extends Zend_View_Helper_Abstract
{
    public $view;

    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ groupscan @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param Ultimatum_Model_Ultplayergroupknowledge $pScan
 * @return string
 */
    public function groupscan (Ultimatum_Model_Ultplayergroupknowledge $pScan, $pContent = NULL) {
        $group = $pScan->get_group();

        ob_start();
        ?>
<fieldset>
    <legend><?= $group->get_title() ?>
        (as of turn <?= $pScan->get_game()->turn(TRUE) ?>)</legend>
    <p><b><?= $group->get_lead() ?></b></p>
    <blockquote>
        <?= $group->get_content() ?>
    </blockquote>
<table>
<tr>
    <th>Value</th>
    <th>Size</th>
    <th>Efficiency</th>
    <th>Effect</th>
    <? foreach(Ultimatum_Model_Ultgroups::$_properties as $property): ?>
    <tr>
        <td><?= ucfirst($property) ?></td>
        <td><?= $pScan->get_size($property, TRUE) ?></td>
        <td><?= $pScan->get_efficiency($property, TRUE) ?></td>
        <td><?= $pScan->get_power($property, TRUE) ?></td>
    </tr>
    <? endforeach ?>
</table>
    <? if ($pContent) echo $pContent; ?>
</fieldset>
        <?
        return ob_get_clean();
    }
}