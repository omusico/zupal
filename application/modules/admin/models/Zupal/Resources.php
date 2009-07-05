<?php

class Zupal_Resources
extends Zupal_Domain_Abstract
implements Zupal_Grid_IGrid
{

    protected static $_Instance = null;

    public function get($pID)
    {
        return new self($pID);
    }

    public function tableClass()
    {
        return 'Zupal_Table_Resources';
    }

    public static function getInstance()
    {
        if (is_null(self::$_Instance)):
        	self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function save ()
	{
		parent::save();
		$cache = Zupal_Bootstrap::$registry->cache->remove('resources');
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Grid Implementation @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * @see Zupal_Grid_IGrid::render_data()
	 *
	 * NOTE: the params argument is unused at this point
	 *
	 * @param array $pParams
	 * @param unknown_type $pStart
	 * @param unknown_type $pRows
	 * @param unknown_type $pSort
	 */
	public function render_data(array $pParams, $pStart = 0, $pRows = 30, $pSort = NULL)
	{
		$cache = Zupal_Bootstrap::$registry->cache;
		$key = 'resources';


		if (!$cache->test($key)):
			$items = $this->table()->fetchAll(NULL, 'label');

			$data = new Zend_Dojo_Data('id', $items, 'id');

			$cache->save($data, $key);
		endif;

		return $cache->load($key);
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

		$columns = array();

		foreach($pColumns as $k => $v) $columns[$k] = $v;
		// todo: add control buttons

		return Zupal_Grid_Maker::grid($pID, $pStore_ID, $columns, $this->table()->idField(), array('onRowClick' => 'res_row_click'));
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ render_script @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * @see Zupal_Grid_IGrid::render_script()
	 *
	 * @param unknown_type $pID
	 * @param array $pParams
	 */
	public function render_script($pID, array $pParams = NULL) {
		//TODO - Insert your code here
		include dirname(__FILE__) . DS . 'res_grid_script.php';
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ render_store @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	 * @see Zupal_Grid_IGrid::render_store()
	 *
	 * @param unknown_type $pStore_ID
	 * @param unknown_type $pURL
	 */
	public function render_store($pStore_ID, $pURL) {
		return Zupal_Grid_Maker::store($pStore_ID, $pURL);
	}

	private function store_url()
	{
		return ZUPAL_BASEURL . '/admin/acl/resdata';
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ acl_resource @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_acl_resource = NULL;
	function as_acl_resource($pReload = FALSE)
	{
		if ($pReload || is_null($this->_acl_resource) || ($this->identity() != $this->_acl_resource->getResourceId())):
			$value = new Zend_Acl_Resource($this->identity());
			$this->_acl_resource = $value;
		endif;
		return $this->_acl_resource;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ grants @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_grants = NULL;
	function grants($pReload = FALSE)
	{
		if ($pReload || is_null($this->_grants)):
			$this->_grants = Zupal_Grants::getInstance()->find(array('resource' => $this->identity()), 'resource');
		endif;
		return $this->_grants;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ grant @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param Zupal_Roles | String $pRole
	* @return boolean | NULL
	*/
	public function grant ($pRole)
	{
		if ($pRole instanceof Zupal_Roles):
			$pRole = $pRole->identity();
		endif;

		$grant = NULL;

		foreach($this->grants() as $g):
			if ($g->role == $pRole):
				$grant = $g;
				break;
			endif;
		endforeach;
		return $grant ? $grant->allow : NULL;
	}

}

