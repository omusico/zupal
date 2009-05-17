<?php

/**
 * Imeplements by extension Zupal_Node, Zupal_Domain
 */
class Zupal_People extends Zupal_Domain_Abstract
implements Zupal_Grid_IGrid
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pSeperator = ' '
	* @return <type>
	*/
	public function name ($pSeperator = ' ')
	{
		$parts = array();

		if ($this->title) $parts[] = $this->title;
		if ($this->name_first) $parts[] = $this->name_first;
		if ($this->name_middle) $parts[] = $this->name_middle;
		if ($this->name_last) $parts[] = $this->name_last;

		return join($pSeperator, $parts);
	}

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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	private static $_fields = NULL;
	public static function fields ()
	{
		if (!self::$_fields):
			$i = self::getInstance();
			$a = $i->toArray();
			$ak = array_keys($a);
			self::$_fields =$ak;
		endif;

		return self::$_fields;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Grid Stuff @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ render_script @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return void;
	*/
	public function render_script ($pID, array $pParams = NULL)
	{
		include_once(dirname(__FILE__) . DS . 'grid_script.php');
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ render_store @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param string $pStoreID
	* @param string $pURL
	* @return void
	*/
	public function render_store ($pStore_ID, $pURL)
	{
		return Zupal_Grid_Maker::store($pStore_ID, $pURL);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ IGrid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

//	public function render_grid(Zend_View $pView, $pID, $pStore_ID, array $pColumns);

	public function render_grid(Zend_View $pView, $pID, $pStore_ID, array $pColumns)
	{
		
		Zupal_Grid_Maker::prep_view($pView);

		$identifier = $this->table()->idField();
		$cache = Zupal_Bootstrap::$registry->cache;
		if (!$cache->test('people_grid')):
?>
	<table id="igrid_<?= $pID ?>_people_node"  rowsPerPage="10" style=" height: 400px" jsId="igrid_<?= $pID ?>" dojoType="dojox.grid.DataGrid" clientSort="true"
	   query="{ <?= $identifier ?> : '*' }" store="<?= $pStore ?>">
	<thead>
		<tr>
			<th get="people_view" width="25">&nbsp;</th>
			<th get="people_edit"  width="25">&nbsp;</th>
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

<?
		endif;

		return $cache->load('people_grid');
	}

//	public function render_data(array $pParams, $pStart = 0, $pRows = 30, $pSort = NULL);
	public function render_data(array $pParams, $pStart = 0, $pRows = 30, $pSort = NULL)
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

		if (!$this->isSaved()):
			$password = $this->password;
		endif;

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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ encrypt_password @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return string
	*/
	public static function encrypt_password ($pass, $seed = 0)
	{
		return crypt(md5($pass), md5($seed) . Zupal_Bootstrap::$registry->configuration->seed);
	}
}