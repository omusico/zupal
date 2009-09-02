<?php
/**
 * This helper renders our a Dojo Grid for a given data set.
 * The columns are passed in as an array of data.
 * Note -- while the dataset has only been tested on singular (table) reports,
 * there is no reason that it cannot represent a joined query also.
 */
class Zupal_Helper_Dojostore {

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ store @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
* a datastore for the grid. Could also be used for other store based widgets.
*/
public static function dojostore ( $pStore_ID, $pURL)
{
    ob_start();
?>
<span dojoType="dojo.data.ItemFileReadStore"
jsId="<?= $pStore_ID ?>" url="<?= $pURL ?>/rand/<?= rand(0, 100000) ?>" />
<?
    return ob_get_clean();
}

}
