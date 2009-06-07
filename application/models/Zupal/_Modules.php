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
	 * @return Zupal_Modules
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
	
	/**
	 * @see Zupal_Grid_IGrid::render_script()
	 *
	 * @param unknown_type $pID
	 * @param array $pParams
	 */
	public function render_script($pID, array $pParams = NULL) 
	{
		include_once (dirname(__FILE__) . DS . 'modules_grid_script.php');
	}
	
	/**
	 * @see Zupal_Grid_IGrid::render_store()
	 *
	 * @param unknown_type $pStore_ID
	 * @param unknown_type $pURL
	 */
	public function render_store($pStore_ID, $pURL) 
	{
		Zupal_Grid_Maker::store($pStore_ID, $pURL);
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
//	public function render_grid(Zend_View $pView, $pID, $pStore_ID, array $pColumns);

	public function render_grid(Zend_View $pView, $pID, $pStore, array $pColumns)
	{
		Zupal_Grid_Maker::prep_view($pView);

		$identifier = $this->table()->idField();
		$cache = Zupal_Bootstrap::$registry->cache;
		if (!$cache->test('modules_grid')):
?>
<span dojoType="dojo.data.ItemFileReadStore"
	jsId="igrid_<?= $pID ?>_module_store"
	url="<?= $pURL ?>/rand/<?= (int) (rand() * 10000) ?>" />
<table id="igrid_<?= $pID ?>_modules_node" rowsPerPage="10"
	style="height: 400px" jsId="igrid_<?= $pID ?>"
	dojoType="dojox.grid.DataGrid" clientSort="true"
	query="{ <?= $identifier ?> : '*' }"
	store="<?= $pStore ?>">
	<thead>
		<tr>
			<th get="modules_view" width="25">&nbsp;</th>
			<th get="modules_edit" width="25">&nbsp;</th>
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
		?><th field="<?= $pKey ?>"
	<?
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
		foreach($pColumn as $key => $value): ?>
	<?= $key ?>="<?= $value ?>" <? endforeach; // parameter loop
?>><?= $label ?></th>
<?
	}

}