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
    public function groupscan (Ultimatum_Model_Ultplayergroupknowledge $pScan) {
        $group = $scan->get_group();

        ob_start();
        ?>
<fieldset>
    <label><?= $group->get_title() ?> (as of turn <?= $pScan->get_game()->turn(TRUE) ?></label>
    <p><b><?= $group->get_lead() ?></b></p>
    <blockquote>
        <?= $group->get_content() ?>
    </blockquote>
<table>
<tr>
    <th>Value</th>
    <td>Size</th>
    <th>Efficiency</th>
    <th>Effect</th>
    <? foreach(Ultimatum_Model_Ultgroups::$_properties as $property): ?>
    <tr>
        <td><?= ucfirst($property) ?></td>
        <td><?= $scan->get_size($property) ?></td>
        <td><?= $scan->get_efficiency($property) ?></td>
        <td><?= $scan->get_effect($property) ?></td>
    </tr>
    <? endforeach ?>
</table>
</fieldset>
        <?
        return ob_get_clean();
    }
}