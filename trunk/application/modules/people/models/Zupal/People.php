<?php

/**
 * Imeplements by extension Zupal_Node, Zupal_Domain
 */
class Zupal_People extends Zupal_Domain_Abstract
implements Zupal_Grid_IGrid
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * @see CPF_Formset_Domain::get_table_class()
	 *
	 */
	public function tableClass ()
	{
		return preg_replace('~^Zupal_~', 'Zupal_Table_', get_class($this));
	}

	/**
	 * @see CPF_Formset_Domain::get()
	 *
	 * @param unknown_type $pID
	 * @return CPF_Formset_Domain
	 *
	 */
	public function get ($pID)
	{
		return new Zupal_People($pID);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ User Stuff @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ locations @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return Zupal_People_Places[]
	*/
	public function places ()
	{
		return $out;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ IGrid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $RENDER_GRID_PARAM_DEFAULTS = array('identifier' => 'id');
	
	public function render_grid($pID, array $pParams = NULL)
	{
		$pParams = array_merge(self::$RENDER_GRID_PARAM_DEFAULTS, $pParams);
		ob_start();
		?>
<table id="igrid_<?= $pID ?>node" jsId="igrid_<?= $pID ?>" dojoType="dojox.grid.DataGrid"
	   query="{ <?= $identifier ?> : '*' }" store="jsonStore">
	<thead>
		<tr>
<? foreach($pParams['columns'] as $key => $column): ?>
	<? if (is_array($column)): ?>
		<?= $this->render_array_column($key, $column) ?>
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
		return ob_end_flush();
	}

	public function render_data(array $pParams, $pSort = NULL, $pStart = 0, $pRows = 30)
	{
		$select = $this->_select($pParams, $pSort);

		$rows = $this->table()->fetchAll($select);
		$items = array();

		foreach($rows as $row):
			$items[] = $row->toArray();
		endforeach;

		$data = new Zend_Dojo_Data($pIdentitfier, $items, $pLabel);

		return $data;
	}

	protected function render_array_column($pKey, $pColumn)
	{
		if (array_key_exists('field', $pColumn)):
			$field = $pColumn['field'];
			unset($pColumn['field']);
		endif;
		?><th field="<?= $key ?>" <?
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

}