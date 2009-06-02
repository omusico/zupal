<?php

class Zupal_Grid_Maker {

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
			->addLayer(ZUPAL_BASEURL . DS . 'scripts/Dojo/scaffold.js.uncompressed.js')
			->addLayer(ZUPAL_BASEURL . DS . 'scripts/Dojo/dojo/nls/scaffold_en-us.js')
			->requireModule('dojox.data.QueryReadStore')
           //  ->requireModule('dojox.grid.DataGrid')
           //  ->requireModule('dojo.data.ItemFileReadStore')
			->addStyleSheet(ZUPAL_BASEURL . DS . 'scripts/Dojo/dojox/grid/resources/Grid.css')
			->enable();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ store @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
public static function store ( $pStore_ID, $pURL) 
{
?>
<span dojoType="dojo.data.ItemFileReadStore"
jsId="<?= $pStore_ID ?>" url="<?= $pURL ?>/rand/<?= rand(0, 100000) ?>" />
<?
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ grid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pParam
	* @return <type>
	*/
public static function grid ($pID, $pStore_ID = NULL, $pColumns, $pIdentifier = 'id', array $pParams = NULL)
{
	if (is_null($pStore_ID)) $pStore_ID = $pID . '_store';
?>
<table id="<?= $pID ?>"  rowsPerPage="10" style=" height: 400px" jsId="<?= $pID ?>"
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
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ querygrid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pParam
	* @return <type>
	*/
public static function querygrid($pID, $pStore_ID = NULL, $pColumns, $pIdentifier = 'id', array $pParams = NULL)
{
	if (is_null($pStore_ID)) $pStore_ID = $pID . '_store';
?>
<table id="<?= $pID ?>"  rowsPerPage="30" style=" height: 400px" jsId="<?= $pID ?>"
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
        return 30;
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
