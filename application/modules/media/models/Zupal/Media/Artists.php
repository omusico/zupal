<?

class Zupal_Media_Artists
extends Zupal_Node_Abstract
implements Zupal_Grid_IGrid
{

	protected $_joins = array(
		'person' => array(
			'local_key' => 'person_id',
			'value' => NULL,
			'class' => 'Zupal_People'
		),
		'media' => array(
			'local_key' => 'media_id',
			'value' => NULL,
			'class' => 'Zupal_Media_Media'
		),
		'artist_mb' => array(
			'local_key' => 'artist_mb',
			'value' => NULL,
			'class' => 'Zupal_Media_MusicBrains_Artist'
		)
	);



/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ field_map @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_field_map = NULL;
	public static function field_map($pReload = FALSE)
	{
		if (is_null(self::$_field_map)):
		// process
			self::$_field_map = array();
			foreach(Zupal_People::fields() as $field):
				self::$_field_map['person_' . $field] = array('join' => 'person', 'field' => $field);
			endforeach;

			foreach(Zupal_Media_Media::fields() as $field):
				self::$_field_map['media_' . $field] = array('join' => 'media', 'field' => $field);
			endforeach;
		endif;
		return self::$_field_map;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ virtual fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 * by overriding the magic get/set, we treate the person fields as if they belonged
 * to this domain. 
 */
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __get @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pField
	* @return scalar
	*/
	public function __get ($pField)
	{
		$map =  self::field_map();
		if (array_key_exists($pField, $map)):
			extract($map[$pField]);
			$j = $this->get_join($join);
			
			if (is_object($j)):
				return $j->$field;
			else:
				//throw new Exception(__METHOD__ . ': cannot find ' . $join);
				return NULL;
			endif;
		endif;
		
		return parent::__get($pField);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __set @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pField, $pValue
	* @return scalar
	*/
	public function __set ($pField, $pValue)
	{
		$map =  self::field_map();
		//$pre = substr($pField, 0, $s);

		if (array_key_exists($pField, $map)):
			extract($map[$pField]);
			
			$j = $this->get_join($join);
			
			if(is_object($j)):
				return $j->$field = $pValue;
			else:
				return NULL;
			endif;
			
		endif;

		return parent::__set($pField, $pValue);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return string
	*/
	public function name ()
	{
		if ($this->performs_as):
			return $this->performs_as;
		else:
			return $this->person()->name();
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ person @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return Zupal_People
	*/
	public function person ($pCreate_if_empty = TRUE)
	{
		return $this->get_join('person', $pCreate_if_empty);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ artist_mb @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	* @return Zupal_Media_MusicBrains_Artists
	*/
	public function artist_mb ($pCreate_if_empty = TRUE)
	{
		if ($ths->artist_mb):
		return $this->get_join('artist_mb', $pCreate_if_empty);
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_person @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param int | Zupal_People $pParam
	* @return
	*/
	public function set_person ($pParam)
	{
		if (is_numeric($pParam)):
			$this->person_id = $pParam;
			if (array_key_exists('people', $this->_joins)):
				unset($this->_joins['people']);
			endif;
		elseif ($pParam instanceof Zupal_People):
			$this->_joins['people'] = $pParam;
			$this->person_id = $pParam->identity();
		endif;
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
 * @return Zupal_Media_Artists
 */
	public static function getInstance()
	{
		if (is_null(self::$_Instance)):
		// process
		self::$_Instance = new self();
		endif;
		return self::$_Instance;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ node interface @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * @see Zupal_Content_IContent::author()
	 *
	 * @return Zupal_Content_Domain
	 */
	public function author() {
		//TODO - Insert your code here
		return NULL;
	}
	
	/**
	 * @see Zupal_Content_IContent::authorId()
	 *
	 */
	public function authorId() {
		//TODO - Insert your code here
		return NULL;
	}
	
	/**
	 * @see Zupal_Content_IContent::isPublic()
	 *
	 * @return boolean
	 */
	public function isPublic() {
		//TODO - Insert your code here

		return TRUE;
	}
	
	/**
	 * @see Zupal_Content_IContent::isPublished()
	 *
	 * @return boolean
	 */
	public function isPublished() {
		//TODO - Insert your code here

		return TRUE;
	}
	
	/**
	 * @see Zupal_Content_IContent::publishDate()
	 *
	 * @return GMT
	 */
	public function publishDate() {
		//TODO - Insert your code here
		return TRUE;
	}
	
	/**
	 * @see Zupal_Content_IContent::rawText()
	 *
	 */
	public function rawText() {
		//TODO - Insert your code here
		return $this->text();
	}
	
	/**
	 * @see Zupal_Content_IContent::shortText()
	 *
	 */
	public function shortText() {
		//TODO - Insert your code here
		return $this->text();
	}
	
	/**
	 * @see Zupal_Content_IContent::shortTitle()
	 *
	 */
	public function shortTitle() {
		//TODO - Insert your code here
		return $this->title();
	}
	
	/**
	 * @see Zupal_Content_IContent::text()
	 *
	 */
	public function text() {
		//TODO - Insert your code here

		return $this->bio;
	}
	
	/**
	 * @see Zupal_Content_IContent::title()
	 *
	 * @return string
	 */
	public function title() {
		//TODO - Insert your code here
		return $this->name();
	}
	
	/**
	 * @see Zupal_Content_IContent::unpublishDate()
	 *
	 * @return GMT
	 */
	public function unpublishDate() {
		//TODO - Insert your code here
		return NULL;
	}
	/**
	 * @see Zupal_Grid_IGrid::render_data()
	 *
	 * @param array $pParams
	 * @param unknown_type $pStart
	 * @param unknown_type $pRows
	 * @param unknown_type $pSort
	 */
	public function render_data(array $pParams, $pStart = 0, $pRows = 30, $pSort = NULL) {
		$cache = Zupal_Bootstrap::$registry->cache;

		if (!$cache->test('artist_data')):

			$select = $this->_select($pParams, $pSort);

			$rows =  $this->table()->getAdapter()->fetchAll($select);
			$items = array();

			foreach($this->findAll('performs_as') as $row):
				$data = $row->toArray();
				foreach($data as $k => $v) if (is_null($v)) $data[$k] = '';

				$person_data = $row->person()->toArray();
				foreach($person_data as $key => $value):
					$data["person_$key"] = is_null($value) ? '' : $value;
				endforeach;
				$items[] = $data;
			endforeach;

			$cache->save(new Zend_Dojo_Data('node_id', $items, 'performs_as'));
		endif;

		return $cache->load('artist_data');
	}
	
	/**
	 * @see Zupal_Grid_IGrid::render_grid()
	 *
	 * @param Zend_View $pView
	 * @param unknown_type $pID
	 * @param unknown_type $pStore_ID
	 * @param array $pColumns
	 */
	public function render_grid(Zend_View $pView, $pID, $pStore_ID, array $pColumns) {
		Zupal_Grid_Maker::prep_view($pView);

		$columns = array(
			'artist_view' => array('width' => 25, 'label' => '&nbsp;', 'get' => 'artist_view'),
			'artist_edit' => array('width' => 25, 'label' => '&nbsp;', 'get' => 'artist_edit'));

		foreach($pColumns as $k => $v) $columns[$k] = $v;

		$columns['artist_delete'] = array('width' => 25, 'label' => '&nbsp;', 'get' => 'artist_delete');


		return Zupal_Grid_Maker::grid($pID, $pStore_ID, $columns, 'node_id', array('onRowClick' => 'artist_row_click'));
	}
	
	/**
	 * @see Zupal_Grid_IGrid::render_script()
	 *
	 * @param unknown_type $pID
	 * @param array $pParams
	 */
	public function render_script($pID, array $pParams = NULL) {
		//TODO - Insert your code here
		include (dirname(__FILE__)) . DS . 'artists_grid_script.php';
	}
	
	/**
	 * @see Zupal_Grid_IGrid::render_store()
	 *
	 * @param unknown_type $pStore_ID
	 * @param unknown_type $pURL
	 */
	public function render_store($pStore_ID, $pURL) {
		return Zupal_Grid_Maker::store($pStore_ID, $pURL);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function save ()
	{
		parent::save();
		$this->clear_cache();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ clear_cache @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function clear_cache ()
	{
		Zupal_Bootstrap::$registry->cache->remove('artist_data');
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ delete @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function delete ()
	{
		parent::delete();
		$this->clear_cache();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __toString @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function __toString ()
	{
		return $this->name();
	}

}