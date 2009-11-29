<?

class Ultimatum_View_Helper_Grouptable {

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ grouptable @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param <type> $pGroups
 * @return <type>
 */
    public function grouptable ($pGroups, $pChoice = FALSE) {
        ob_start();
        ?>
<table>
    <tr> 
        <? if ($pChoice): ?>
        <th>&nbsp;</th>
        <? endif; ?>
        <th>Group</th>
        <th>Size</th>
        <th>Off.</th>
        <th>Def.</th>
        <th>Net.</th>
        <th>Grow.</th>
    </tr>
<? foreach($pGroups as $pGroup): ?>
    <tr>
        <? if ($pChoice): ?>
        <td><input type="radio" name="group" value="<?= $pGroup->identity() ?>" /></td>
        <? endif; ?>
        <td><?= $pGroup ?></td>
        <td><?= $pGroup->offense_effect() ?></td>
        <td><?= $pGroup->defense_effect() ?></td>
        <td><?= $pGroup->network_effect() ?></td>
        <td><?= $pGroup->growth_effect() ?></td>
    </tr>
    
<? endforeach; ?>
    
</table>
        
       <? 
        return ob_get_clean();
    }
}
