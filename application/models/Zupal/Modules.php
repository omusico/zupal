<?

class Zupal_Modules
extends Zupal_Domain_Abstract
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * @see CPF_Formset_Domain::get()
	 *
	 * @param unknown_type $pID
	 * @return CPF_Formset_Domain
	 *
	 */
	public function get ($pID)
	{
		return new Zupal_Modules($pID);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	 *
	 * note -- this "boilderplate" can be dropped anywhere.
	 */
	private static $_Instance = NULL;

	/**
	 * @return self
	 */
	public static function getInstance()
	{
		if (is_null(self::$_Instance)):
		// process
		self::$_Instance = new Zupal_Modules();
		endif;
		return self::$_Instance;
	}

	public static function module($pName){
		$pName = strtolower($pName);
		return self::getInstance()->get($pName);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return void
	*/
	public function save ()
	{
		if ($this->package == 'core') $this->enabled = 1;
		parent::save();
	}
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ IGrid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function render_grid(Zend_View $pView, $pID, array $pColumns, $pURL)
	{
		$pView->dojo()
             ->requireModule('dojox.grid.DataGrid')
             ->requireModule('dojo.data.ItemFileReadStore')
			 ->addStyleSheet('http://ajax.googleapis.com/ajax/libs/dojo/1.2.0/dojox/grid/resources/Grid.css')
			 ->enable();

		$identifier = $this->table()->idField();
		$cache = Zupal_Bootstrap::$registry->cache;
		if (!$cache->test('modules_grid')):
?>
<span dojoType="dojo.data.ItemFileReadStore" jsId="igrid_<?= $pID ?>_module_store" url="<?= $pURL ?>/rand/<?= (int) (rand() * 10000) ?>" />
<table id="igrid_<?= $pID ?>_modules_node"  rowsPerPage="10" style=" height: 400px" jsId="igrid_<?= $pID ?>" dojoType="dojox.grid.DataGrid" clientSort="true"
	   query="{ <?= $identifier ?> : '*' }" store="igrid_<?= $pID ?>_module_store">
	<thead>
		<tr>
			<th get="modules_view" width="25">&nbsp;</th>
			<th get="modules_edit"  width="25">&nbsp;</th>
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
		</tr>
	</thead>
</table>
<script language="javascript">

	function modules_identity(id, item)
	{

		var g = dijit.byId('igrid_<?= $pID ?>_modules_node');

		return g.store.getValue(item, 'name');

	}

	function modules_view(id, item)
	{
		if (!item) return this.defaultValue;

		var g = dijit.byId('igrid_<?= $pID ?>_modules_node');

	//	id = g.store.getValue(item, 'id');

	return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/admin/modules/view/name/' + modules_identity(id, item) + '">'
		+ '<?= Zupal_Image::icon('view')  ?></a>';
	}


	function modules_edit(id, item)
	{
		if (!item) return this.defaultValue;

		var g = dijit.byId('igrid_<?= $pID ?>_modules_node');

	//	id = g.store.getValue(item, 'id');

	return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/admin/modules/edit/name/' +  modules_identity(id, item)  + '">'
		+ '<?= Zupal_Image::icon('edit')  ?></a>';
	}

	function format_bool(b)
	{

		if (b) return '<div style="background-color: green; text-align: center; padding: 3px; color: #CFC; font-weight: bold">Y</div>';
		return '<div style="background-color: red; text-align: center; padding: 3px; color: #FCC; font-weight: bold">N</div>';
	}

	function format_bold(v){ return '<b>' + v + '</b>'; }

</script>
<?
		endif;

		return $cache->load('modules_grid');
	}

	public function render_data(array $pParams, $pSort = NULL, $pStart = 0, $pRows = 30)
	{
		$cache = Zupal_Bootstrap::$registry->cache;

		if (!$cache->test('modules_data')):

			$select = $this->_select($pParams, $pSort);

			$rows = $this->table()->fetchAll($select);
			$items = array();

			foreach($rows as $row):
				if ($row == 'password') continue;
				$items[] = $row->toArray();
			endforeach;

			$cache->save(new Zend_Dojo_Data($this->table()->idField(), $items, 'email'));
		endif;

		return $cache->load('modules_data');
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

}