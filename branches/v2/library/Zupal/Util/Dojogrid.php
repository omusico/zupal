<?
class Zupal_Util_Dojogrid
{
    
    public static function declarative($pID, $pRows, $query_string, $pStore_id, $height, $width, $pParams){
	ob_start();
    ?>
	<table rowsPerPage="10" style="height: <?= $height ?>px; width: <?=$width ?>px" id="<?= $pID ?>"
	       dojoType="dojox.grid.DataGrid" clientSort="true" <?= self::params($pParams) ?>
	       query="<?= $query_string ?>" store="<?= $pStore_id ?>">
	    <thead>
<? foreach($pRows as $columns): ?>
		<tr>
<? foreach($columns as $key => $column): ?>
    <? if (is_array($column)): ?>
	<?= self::array_column($column) ?>
    <? elseif (is_string($column)): ?>
<th field="<?= trim($key) ?>"> <?= $column ?></th>
	<? elseif (is_object($column)): // must have a __toString() method ?>
	    <?= $column ?>
	<? endif; ?>
<? endforeach; ?>
		</tr>
<? endforeach; ?>
	    </thead>
	</table>
<?
    return ob_get_clean();
}


    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ params @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param array $pParam
 * @return string
 */
    public static function params ($pParam) {
	$out = '';
	if ($pParam):
	    foreach($pParam as $k => $v):
		$out .= sprintf(' %s="%s" ', $k, $v);
	    endforeach;
	endif;
	return $out;
    }

    private static function _format_formatter($pString)
    {
        $str = preg_replace('~"(formatter|get)"\s*:\s*"([\w]+)"~', '$1: $2', $pString);
	return 
	str_replace(array('"true"', '"false"'), array('true', 'false'), 
	$str);
    }

    public static function interp (Zend_View $view, $pID, $pRows, $query_string, $pStore_id, $height, $width, $pParams)
    {
        if (is_object($pRows)) $pRows = $pRows->toArray();
	ob_start();

	?>
	<div id="<?= $pID ?>_node" style="height: <?= $height ?>px; width: <?= $width ?>px"></div>
	    <? $view->headScript()->captureStart(); ?>

function areweloading()
{
    console.debug('yes we are');
}


function make_dojogrid_<?= $pID ?>()
{
    var layout = [
	<? ob_start();
	foreach($pRows as $row):  ?>
	    [
	<? $fields = array();  foreach($row as $col):?>
    <? $fields[] = self::_format_formatter(Zend_Json::encode($col)) ?>
<? endforeach;
echo join(',', $fields);
?>],<? endforeach; 
	echo rtrim(ob_get_clean(), ", \n\r") ?>

    ];
    console.debug('made layout');
    console.debug(layout);

    var grid = new dojox.grid.DataGrid(
    {
	    query: <?= $query_string ?>,
	    structure: layout,
	    store: <?= $pStore_id ?>
    });
    console.debug('grid made');
        dojo.byId('<?= $pID ?>_node').appendChild(grid.domNode);
    grid.startup();

}
dojo.addOnLoad(areweloading);

dojo.addOnLoad(make_dojogrid_<?= $pID ?>);
	    <?
	    $view->headScript()->captureEnd();
	return ob_get_clean();
    } 
}