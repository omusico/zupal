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
             ->requireModule('dojox.grid.DataGrid')
             ->requireModule('dojo.data.ItemFileReadStore')
			 ->addStyleSheet('http://ajax.googleapis.com/ajax/libs/dojo/1.2.0/dojox/grid/resources/Grid.css')
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ grid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pParam
	* @return <type>
	*/
public static function grid ($pID, $pStore_ID = NULL, $pColumns, $pIdentifier = 'id')
{
	if (is_null($pStore_ID)) $pStore_ID = $pID . '_store';
?>
<table id="<?= $pID ?>"  rowsPerPage="10" style=" height: 400px" jsId="<?= $pID ?>"
dojoType="dojox.grid.DataGrid" clientSort="true"
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

}
