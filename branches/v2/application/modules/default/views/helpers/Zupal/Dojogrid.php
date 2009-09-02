<?php
/**
 * This helper renders our a Dojo Grid for a given data set.
 * The columns are passed in as an array of data.
 * Note -- while the dataset has only been tested on singular (table) reports,
 * there is no reason that it cannot represent a joined query also.
 */
class Zupal_Helper_Dojogrid {

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ grid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param string $pID - the javascript identity of the table
	* @param string $pStore_ID - the name of the datastore identity
	* @param array $pColumns - a parameter set for thecolumn definitions
	* @return string $pIdentifier -- the name of the identity column of the dataset
	* 
	*/
public static function dojogrid ($pID, $pStore_ID = NULL,
    $pColumns, $pIdentifier = 'id', array $pParams = NULL)
{
    ob_start();
	if (is_null($pStore_ID)):
	    $pStore_ID = $pID . '_store';
	endif;
?>
<table rowsPerPage="10" style=" height: 400px" id="<?= $pID ?>"
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
</table>
<?
return ob_get_clean();
	}

	public static function array_column($pKey, $pColumn)
	{
		if (array_key_exists('field', $pColumn)):
			$field = $pColumn['field'];
			unset($pColumn['field']);
		endif;
		?><th field="<?= $pKey ?>" <?
		if (array_key_exists('label', $pColumn)): // Is a keyed set of parameters.
			$label = $pColumn['label'];
			unset($pColumn['label']);
		else: // IS NOT a keyed set of parameters
			$label = array_shift($pColumn);
			if (count($pColumn)): // take next parameter as width -- tranform it to named parameter.
				$width = array_shift($pColumn);
				$pColumn['width'] = $width;
			endif;
		endif; // end parameter loop
		foreach($pColumn as $key => $value): ?> <?= $key ?>="<?= $value ?>" <? endforeach; // parameter loop
?> ><?= $label ?></th>
<?
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ prep_view @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param Zend_View $pView
	* @return void
	*/
	public static function prep_view (Zend_View $pView)
	{

		$pView->dojo()
			->addLayer(ZUPAL_BASEURL . DS . 'scripts/dojo/grid_layer.js')
			->addStyleSheet(ZUPAL_BASEURL . DS . 'scripts/dojox/grid/resources/Grid.css')
			->enable();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ store @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
* a datastore for the grid. Could also be used for other store based widgets.
*/
public static function store ( $pStore_ID, $pURL) 
{
    ob_start();
?>
<span dojoType="dojo.data.ItemFileReadStore"
jsId="<?= $pStore_ID ?>" url="<?= $pURL ?>/rand/<?= rand(0, 100000) ?>" />
<?
    return ob_get_clean();
}

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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ params @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param array $pParam
	* @return string
	*/
public static function params ($pParam)
{
	$out = '';
	if ($pParam):
		foreach($pParam as $k => $v):
			$out .= sprintf(' %s="%s" ', $k, $v);
		endforeach;
	endif;
	return $out;
}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ querygrid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
        * for large datasets - pulls a section of the grid at a time
	* @param <type> $pParam
	* @return <type>
	*/
public static function querygrid($pID, $pStore_ID = NULL, $pColumns, $pIdentifier = 'id', array $pParams = NULL)
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
