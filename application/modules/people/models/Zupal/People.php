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
	 * -- note -- this is "boilerplate" code that can be put into any new domain
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
		return new self($pID);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * note -- this "boilderplate" can be dropped anywhere.
 */
	private static $_Instance = NULL;

/**
 *
 * @return Zupal_People
 */
	public static function getInstance()
	{
		if (is_null(self::$_Instance)):
		// process
		self::$_Instance = new self();
		endif;
		return self::$_Instance;
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
	
	public function render_grid($pID, array $pColumns, $pURL)
	{
		$identifier = $this->table()->idField();
		$cache = Zupal_Bootstrap::$registry->cache;
		if (!$cache->test('people_grid')):
?>
<span dojoType="dojo.data.ItemFileReadStore" jsId="igrid_<?= $pID ?>_store" url="<?= $pURL ?>/rand/<?= (int) (rand() * 10000) ?>" />
<table id="igrid_<?= $pID ?>_people_node"  rowsPerPage="10" style=" height: 400px" jsId="igrid_<?= $pID ?>" dojoType="dojox.grid.DataGrid" clientSort="true"
	   query="{ <?= $identifier ?> : '*' }" store="igrid_<?= $pID ?>_store">
	<thead>
		<tr>
			<th get="people_view" width="25">&nbsp;</th>
			<th get="people_edit"  width="25">&nbsp;</th>
			<th field="person_id"  width="60">ID</th>
<? foreach($pColumns as $key => $column): ?>
	<? if (is_array($column)): ?>
		<?= $this->render_array_column($key, $column) ?>
<? elseif (is_string($column)): ?>
			<th field="<?= $key ?>"><?= $column ?></th>
<? elseif (is_object($column)): // must have a __toString() method
?>
			<?= $column ?>
<? endif; ?>
<? endforeach; ?>
			<th get="people_delete" width="25" >&nbsp;</th>

		</tr>
	</thead>
</table>
<script language="javascript">

	function people_identity(id, item)
	{

		var g = dijit.byId('igrid_<?= $pID ?>_people_node');

		return g.store.getValue(item, 'person_id');

	}

	function people_view(id, item)
	{
		if (!item) return this.defaultValue;

		var g = dijit.byId('igrid_<?= $pID ?>_people_node');

	//	id = g.store.getValue(item, 'id');

	return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/people/item/view/id/' + people_identity(id, item) + '">'
		+ '<?= Zupal_Image::icon('view')  ?></a>';
	}


	function people_edit(id, item)
	{
		if (!item) return this.defaultValue;

		var g = dijit.byId('igrid_<?= $pID ?>_people_node');

	//	id = g.store.getValue(item, 'id');

	return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/people/item/edit/id/' +  people_identity(id, item)  + '">'
		+ '<?= Zupal_Image::icon('edit')  ?></a>';
	}


	function people_delete(id, item)
	{
		if (!item) return this.defaultValue;

		var g = dijit.byId('igrid_<?= $pID ?>_people_node');

	//	id = g.store.getValue(item, 'id');

	return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/people/item/delete/id/' +  people_identity(id, item)  + '">'
		+ '<?= Zupal_Image::icon('x')  ?></a>';
	}

</script>
<?
		endif;

		return $cache->load('people_grid');
	}

	public function render_data(array $pParams, $pSort = NULL, $pStart = 0, $pRows = 30)
	{
		$cache = Zupal_Bootstrap::$registry->cache;

		if (!$cache->test('people_data')):

			$select = $this->_select($pParams, $pSort);

			$rows = $this->table()->fetchAll($select);
			$items = array();

			foreach($rows as $row):
				if ($row == 'password') continue;
				$items[] = $row->toArray();
			endforeach;

			$cache->save(new Zend_Dojo_Data($this->table()->idField(), $items, 'email'));
		endif;

		return $cache->load('people_data');
	}

	protected function render_array_column($pKey, $pColumn)
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

	public function save()
	{
		$logger = Zupal_Module_Manager::getInstance()->get('people')->logger();
		parent::save();
		$logger->info('Person ' . $this->identity() . ' saved');
		$cache = Zupal_Bootstrap::$registry->cache;
		$cache->remove('people_data');
	}
	public function delete()
	{
		$logger = Zupal_Module_Manager::getInstance()->get('people')->logger();
		$logger->info('Person ' . $this->identity() . ' deleted');
		$cache = Zupal_Bootstrap::$registry->cache;
		$cache->remove('people_data');
		parent::delete();
	}

}