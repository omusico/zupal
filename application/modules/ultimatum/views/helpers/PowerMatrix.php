<?

class Ultimatum_View_Helper_PowerMatrix
extends Zend_View_Helper_Abstract
{

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ powerMatrix @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param Ultimatum_Model_GroupProfileIF $pParam
 * @return <type>
 */
    public function powerMatrix (Ultimatum_Model_GroupProfileIF $profile) {
        ob_start();
        ?>
<table class="ult_power_matrix">
    <tr>
        <th>Off</th>
        <th>Def</th>
        <th>Grow</th>
        <th>Net</th>
    </tr>
    <? if (Ultimatum_Model_Ultgames::get_active()): ?>
    <tr>
        <td><small><?= $profile->offense_size(TRUE) ?> &times; <?= $profile->offense_efficiency(TRUE) ?></small></td>
        <td><small><?= $profile->defense_size(TRUE) ?> &times; <?= $profile->defense_efficiency(TRUE) ?></small></td>
        <td><small><?= $profile->growth_size(TRUE) ?> &times; <?= $profile->growth_efficiency(TRUE) ?></small></td>
        <td><small><?= $profile->network_size(TRUE) ?> &times; <?= $profile->network_efficiency(TRUE) ?></small></td>
        </tr>
    <tr>
        <td><?= $profile->offense_effect(TRUE) ?></td>
        <td><?= $profile->defense_effect(TRUE) ?></td>
        <td><?= $profile->growth_effect(TRUE) ?></td>
        <td><?= $profile->network_effect(TRUE) ?></td>
    </tr>
    <? else: ?>
    <tr>
        <td><?= $profile->offense_efficiency(TRUE) ?></td>
        <td><?= $profile->defense_efficiency(TRUE) ?></td>
        <td><?= $profile->growth_efficiency(TRUE) ?></td>
        <td><?= $profile->network_efficiency(TRUE) ?></td>
    </tr>

    <? endif; ?>
</table>

<?
        return ob_get_clean();
    }
}