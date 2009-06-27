<?

class Zupal_Users
extends Zupal_People
{


	public function render_grid(Zend_View $pView, $pID, $pStore_ID, array $pColumns )
	{

		Zupal_Grid_Maker::prep_view($pView);

		$identifier = $this->table()->idField();
		$cache = Zupal_Bootstrap::$registry->cache;
		if (!$cache->test('user_grid')):

		ob_start();

		Zupal_grid_Maker::grid($pID, $pStore_ID, $pColumns, $this->table()->idField());

		$cache->save(ob_get_clean(), 'user_grid');
		endif;
		return $cache->load('user_grid');
	}

	public function render_data(array $pParams, $pStart = 0, $pRows = 30, $pSort = NULL)
	{
		$cache = Zupal_Bootstrap::$registry->cache;

		if (!$cache->test('user_data')):
			if (!array_key_exists('username', $pParams)):
				$pParams['username'] = array('', '!=');
			endif;

			$select = $this->_select($pParams, $pSort);
			$sql = $select->assemble;
			
			$rows = $this->table()->fetchAll($select);
			$items = array();

			foreach($rows as $row):
				if ($row == 'password') continue;
				$items[] = $row->toArray();
			endforeach;

			$cache->save(new Zend_Dojo_Data($this->table()->idField(), $items, 'email'));
		endif;

		return $cache->load('user_data');
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	private $_table = NULL;
	function table()
	{
		if (is_null($this->_table)):
		// process
			$this->_table = new Zupal_Table_People();
		endif;
		return $this->_table;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ UserInstance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_UserInstance = NULL;
	public static function GetUserInstance($pReload = FALSE)
	{
		if ($pReload || is_null(self::$_UserInstance)):
		// process
			self::$_UserInstance = new self(Zupal_Domain_Abstract::STUB);
		endif;
		return self::$_UserInstance;
	}

}