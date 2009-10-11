<?php
/**
 * This helper renders our a Dojo Grid for a given data set.
 * The columns are passed in as an array of data.
 * Note -- while the dataset has only been tested on singular (table) reports,
 * there is no reason that it cannot represent a joined query also.
 */
class View_Helper_Dojostore
 extends Zend_View_Helper_Abstract
 {

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ store @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
* a datastore for the grid. Could also be used for other store based widgets.
*/
public function dojostore ( $pStore_ID, $pURL)
{
    $d = $this->view->dojo();
    $d->requireModule('dojo.data.ItemFileReadStore');
    ob_start();
?>

<!-- @@@@@@@@@@@@@@@@@@ FILE STORE @@@@@@@@@@@@@@@@@@ -->
<span dojoType="dojo.data.ItemFileReadStore"
jsId="<?= $pStore_ID ?>" url="<?= $pURL ?>/rand/<?= rand(0, 100000) ?>" />
<!-- @@@@@@@@@@@@@@@@@@ END FILE STORE @@@@@@@@@@@@@@@@@@ -->

<?
    return ob_get_clean();
}

}
