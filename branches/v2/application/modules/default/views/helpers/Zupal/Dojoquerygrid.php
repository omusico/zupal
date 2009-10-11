<?
class View_Helper_Dojoquerygrid
 extends Zend_View_Helper_Abstract
{
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ querygrid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
        * for large datasets - pulls a section of the grid at a time
	* @param <type> $pParam
	* @return <type>
	*/
public static function dojoquerygrid($pID, $pStore_ID = NULL, $pColumns, $pIdentifier = 'id', array $pParams = NULL)
{
	if (is_null($pStore_ID)) $pStore_ID = $pID . '_store';
	$rows_per_page = 100;
	$table_height = 400;
	$table_width = 600;
	if ($pParams && is_array($pParams)):
		extract($pParams);
	endif;
?>
<table id="<?= $pID ?>"  rowsPerPage="<?= $rows_per_page ?>" style=" height: <?= $table_height ?>px; width: <?= $table_width ?>px" jsId="<?= $pID ?>"
dojoType="dojox.grid.DataGrid" clientSort="true" <?= self::params($pParams) ?>
	   query="{ <?= $pIdentifier ?> : '*' }" store="<?= $pStore_ID ?>">
	<thead>
		<tr>
<? foreach($pColumns as $key => $column): ?>
	<? if (is_array($column)): ?>
		<?= self::array_column($key, $column) ?>
	<? elseif (is_string($column)): ?>
			<th field="<?= $key ?>"><?= $column ?></th>
	<? elseif (is_object($column)): // must have a __toString() method
	?>
			<?= $column ?>
	<? endif; ?>
<? endforeach; ?>
		</tr>
	</thead>
<script type="dojo/method" event="requestRows" args="inRowIndex, inCount">
        // creates serverQuery-parameter
        var row  = inRowIndex || 0;
        var params = {
            start: row,
            count: inCount || this.rowsPerPage,
            serverQuery: dojo.mixin(
              { start: row,
                count: inCount || this.rowsPerPage,
                sort:(this.sortColumn || '')
              },
              this.query
            ),
            query: this.query,
            // onBegin: dojo.hitch(this, "beginReturn"),
            onComplete: dojo.hitch(this, "processRows")
        }
        this.store.fetch(params);
    </script>
    <script type="dojo/method" event="getRowCount">
        // should return total count (fetch from server), not "rowsPerPage"
        return <?= $rows_per_page ?>;
    </script>
    <script type="dojo/method" event="sort" args="colIndex">
        // clears old data to force loading of new, then requests new rows
        this.clearData();
        this.sortColumn = colIndex;
        this.requestRows();
    </script>
    <script type="dojo/method" event="setData" args="inData">
        // edited not to reset the store
        this.data = [];
        this.allChange();
    </script>
    <script type="dojo/method" event="canSort">
        // always true
        return true;
    </script>
	</table>
<?

}
}