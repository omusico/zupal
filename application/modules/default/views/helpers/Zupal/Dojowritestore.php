<?php
/**
 * This helper renders our a Dojo Grid for a given data set.
 * The columns are passed in as an array of data.
 * Note -- while the dataset has only been tested on singular (table) reports,
 * there is no reason that it cannot represent a joined query also.
 */
class Zupal_Helper_Dojowritestore {


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ View @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_view = null;
    /**
     * @return Zend_View;
     */
    public function getView() { return $this->_view; }

    public function setView($pValue) { $this->_view = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ store @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
* a datastore for the grid. Could also be used for other store based widgets.
*/
public function dojowritestore ( $pStore_ID, $pURL, $pWrite_handler)
{

    $this->getView()->dojo()->requireModule('dojo.data.ItemFileWriteStore');
    ob_start();
?>
<span dojoType="dojo.data.ItemFileWriteStore"
jsId="<?= $pStore_ID ?>" url="<?= $pURL ?>/rand/<?= rand(0, 100000) ?>" >
    <script type="dojo/connect" event="onSet" args="item, attribute, oldValue, newValue" >
        <?= $pWrite_handler ?>
    </script>
</span>
<?
    return ob_get_clean();
}

}
