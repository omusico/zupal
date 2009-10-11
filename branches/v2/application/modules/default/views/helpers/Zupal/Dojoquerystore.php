<?
class View_Helper_Dojoquerygrid
 extends Zend_View_Helper_Abstract
{

    public static function query_store($pStore_ID, $pURL)
    {
	ob_start();
    ?>
    <div dojoType="dojox.data.QueryReadStore"
	jsId="<?= $pStore_ID ?>"
	url="<?= $pURL ?>"
	doClientPaging="false" />
    <?
	return ob_get_clean();
    }
}