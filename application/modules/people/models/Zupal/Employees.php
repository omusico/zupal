<?

class Zupal_Employees
extends Zupal_Node_Abstract
implements Zupal_Grid_IGrid
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ IGrid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function render_grid($pID, array $pColumns, $pURL)
	{

		$identifier = $this->table()->idField();
		$cache = Zupal_Bootstrap::$registry->cache;
		if (!$cache->test('employee_grid')):
?>
<span dojoType="dojo.data.ItemFileReadStore" jsId="igrid_<?= $pID ?>_store" url="<?= $pURL ?>/rand/<?= (int) (rand() * 10000) ?>" />
<table id="igrid_<?= $pID ?>_employee_node"  rowsPerPage="10" style=" height: 400px" jsId="igrid_<?= $pID ?>" dojoType="dojox.grid.DataGrid" clientSort="true"
	   query="{ <?= $identifier ?> : '*' }" store="igrid_<?= $pID ?>_store">
	<thead>
		<tr>
			<th get="employee_view" width="25">&nbsp;</th>
			<th get="employee_edit"  width="25">&nbsp;</th>
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
			<th get="employee_delete" width="25" >&nbsp;</th>

		</tr>
	</thead>
</table>
<?
		endif;

		return $cache->load('employee_grid');
	}

	public function render_script(array $pData = NULL) {

		$cache = Zupal_Bootstrap::$registry->cache;
		if (!$cache->test('employee_grid_script')):
	?>
<script language="javascript">

	function employee_identity(id, item)
	{

		var g = dijit.byId('igrid_<?= $pID ?>_employee_node');

		return g.store.getValue(item, 'eid');

	}

	function employee_view(id, item)
	{
		if (!item) return this.defaultValue;

		var g = dijit.byId('igrid_<?= $pID ?>_employee_node');

	//	id = g.store.getValue(item, 'id');

	return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/people/employee/view/id/' + employee_identity(id, item) + '">'
		+ '<?= Zupal_Image::icon('view')  ?></a>';
	}


	function employee_edit(id, item)
	{
		if (!item) return this.defaultValue;

		var g = dijit.byId('igrid_<?= $pID ?>_employee_node');

	//	id = g.store.getValue(item, 'id');

	return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/people/employee/edit/id/' +  employee_identity(id, item)  + '">'
		+ '<?= Zupal_Image::icon('edit')  ?></a>';
	}


	function employee_delete(id, item)
	{
		if (!item) return this.defaultValue;

		var g = dijit.byId('igrid_<?= $pID ?>_employee_node');

	//	id = g.store.getValue(item, 'id');

	return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/people/employee/delete/id/' +  employee_identity(id, item)  + '">'
		+ '<?= Zupal_Image::icon('x')  ?></a>';
	}

</script>
<?
		endif;

		return $cache->load('employee_grid_script');
	}

	public function render_data(array $pParams, $pSort = NULL, $pStart = 0, $pRows = 30)
	{
		$cache = Zupal_Bootstrap::$registry->cache;

		if (!$cache->test('employee_data')):

			$select = $this->_select($pParams, $pSort);

			$rows = $this->table()->getAdapter()->fetchAll($select);
			$items = array();

			foreach($rows as $row):
				$person = new Zupal_People($row['person_id']);

				if ($person->is_saved()):
					$row['employee_name_last'] = $person->name_last;
					$row['employee_name_first'] = $person->name_first;
					$row['employee_email'] = $person->email;
					$row['employee_gender'] = $person->gender;
				endif;

				$items[] = $row;
			endforeach;

			$cache->save(new Zend_Dojo_Data($this->table()->idField(), $items, $pSort));
		endif;

		return $cache->load('employee_data');
	}

	protected function render_array_column($pKey, $pColumn)
	{
		return Zupal_Grid_Maker::array_column($pKey, $pColumn);
	}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ content @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
		/**
	 * A string labelf or the content. 
	 * @return string
	 */
	public function title()
	{
		return 'Employee ' . $this->person()->__toString();
	}

	/**
	 * A shorter title, for menus and lists.
	 */
	public function shortTitle(){ return $this->title(); }

	/**
	 * Content, in HTML
	 */
	public function text() { 
		ob_start();
		
?>
<dl>
<? foreach($this->toArray() as $f => $v): ?>
	<dt><?= $f ?>&nbsp;</dt>
	<dd><?= $v ?>&nbsp;</dd>
<? endforeach; ?>	
</dl>
<?
	return ob_get_clean();
	}

	/**
	 * A summary, useful for search listings, home page content, sidebars, aggregation, etc.
	 * Can be equal to text(), or blank.
	 */
	public function shortText()
	{
		return $this->text();
	}

	/**
	 * Allows for coded non-marked-up text including raw ASCII, wiki, etc.
	 * If you don't filter content, can be identical to text().
	 */
	public function rawText()
	{
		$out = '';
		
		foreach($this->toArray() as $f => $v):
			$out .= $f . ': ' . $v . "\n";		
		endforeach;
	}

	/**
	 * determines whether the content is within the publish dates and is public. 
	 * @return boolean
	 */
	public function isPublished()
	{
		return TRUE;
	}
	/**
	 * the expected or past date to expose the content.
	 * Can be left blank for content still in review.
	 *
	 * @return GMT timestamp
	 */
	public function publishDate()
	{
		return $this->hire_date;
	}

	/**
	 * the final expected date to expose the content.
	 * Can be left blank for persistent content
	 * or content in review.
	 *
	 * @return GMT tiestamp
	 */
	public function unpublishDate()
	{
		return $this->fire_date;
	}

	/**
	 * The domain object of the author.
	 * @return Zupal_Content_Domain
	 */
	public function author(){ return NULL; }
	public function authorId(){ return 0; }

	/**
	 * Indicates this content item has been approved for general view.
	 * if public is not public, only authorized people can view it -- in general, the admins,
	 * but ultimately determined by the role system.
	 * @return boolean
	 */
	public function isPublic (){
		return FALSE;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ toArray() @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function toArray()
	{
		$array = parent::toArray();

		$array['name_first'] = $this->employee_name_first;
		$array['name_last'] = $this->employee_name_last;
		return $array;
		
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ node @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	 * returns the identity of the node attached to this object.
	 * @return scalar
	 */

	public function nodeId()
	{
		return $this->{$this->node_field()};
	}

	protected function node_field()
	{
		return 'node_id';
	}
	/**
	 * returns the DOMAIN OBJECT of the node attached to this object.
	 *
	 * @var Zupal_Nodes
	 */
	private $_node = NULL;

	/**
	 *
	 * @param boolean $pReload
	 * @return Zupal_Nodes
	 */
	function node($pReload = FALSE)
	{
		if ($pReload || (is_null($this->_node))):
			$this->_node = new Zupal_Nodes($this->nodeId());
			$this->_node->save();
		endif;

		return $this->_node;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_by_node @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function get_by_node ($pNode_id)
	{
		$table = $this->table();
		$select = $table->select()
		->where($this->node_field() . ' = ?', $pNode_id)
		->order($table->idField() . ' DESC');
		$row = $table->fetchRow($select);
		return $this->get($row);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ person @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_person = NULL;
	function person($pReload = FALSE)
	{
		if ($pReload || is_null($this->_person)):
			if ($this->person_id):
				$person = new Zupal_People($this->person_id);
				if (!$person->isSaved()):
					$person = new Zupal_People();
					$person->save();
					$this->person_id = $person->identity();
				endif;
			else:
				$person = new Zupal_People();
				$person->save();
				$this->person_id = $person->identity();
			endif;
		// process
			$this->_person = $person;
		endif;
		return $this->_person;
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __get @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param string $pField
	* @return variant
	*/
	public function __get ($pField)
	{
		if (preg_match('~^employee_(.*)$~', $pField, $match)):
			return $this->person()->{$match[1]};
		else:
			return parent::__get($pField);
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __set @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param string $pField
	* @param $pValue
	* @return void
	*/
	public function __set ($pField, $pValue)
	{
		if (preg_match('~^employee_(.*)$~', $pField, $match)):
			$this->person()->{$match[1]} = $pValue;
		else:
			return parent::__set($pField, $pValue);
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public function save()
	{
		$this->person()->save();
		$this->person_id = $this->person()->identity();
		parent::save();
	}
}