<?

class Ultimatum_View_Helper_Groupscan
extends Zend_View_Helper_Abstract
{
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ groupscan @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param Ultimatum_Model_Groupscan $pScan
 * @return string
 */
    public function groupscan ($pScan) {
        ob_start();
        ?>
<table>
<? foreach($pScan->toArray()  as $field => $value): ?>
        <tr><th><?= $field ?></th>
            <td><?= $value ?></td>
        </tr>
        <? endforeach;
        
        return ob_get_clean();
    }
}