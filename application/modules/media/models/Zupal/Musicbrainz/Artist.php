<?php

class Zupal_Musicbrainz_Artist
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
        return 'Zupal_Table_Musicbrainz_Artist';
    }


/**
 *
 * @return Zupal_Musicbrainz_Artist
 */
    public static function getInstance()
    {
        if (is_null(self::$_Instance)):
        	self::$_Instance = new self();
        endif;
        return self::$_Instance;
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
	public function render_data(array $pParams, $pStart = 0, $pRows = 400, $pSort = 'name')
	{
		$cache = Zupal_Media_MusicBrainz_Cache::getInstance();
		$key = 'artists_' . $pStart . '_' . $pRows . '_' . $pSort;
		if (array_key_exists('name', $pParams)):
			$name = $pParams['name'];
			$key .= '_' . trim(preg_replace('~[\W]+~', '_', $name));
		else:
			$name = '';
		endif;

		if (!$cache->test($key)):
			$count_sql = sprintf('SELECT count(id) FROM `%s`', $this->table()->tableName());
			if ($name):
				$pParams['name'] = "%$name%";
				$count_sql .= " WHERE `name` LIKE '%$name%'";
			endif;
			error_log(__METHOD__ . ': count = '. $count_sql);
			$select = $this->_select($pParams, $pSort);
			
			$select->limit($pRows, $pStart);
			$fsql = $select->assemble();
			error_log(__METHOD__ . ': '. $fsql);
			
			$rows = $this->table()->fetchAll($select);
			$items = array();

			foreach($rows as $row):
				$data = $row->toArray();
				foreach($data as $k => $v) if (is_null($v)) $data[$k] = '';
				$items[] = $data;
			endforeach;

			$count = $this->table()->getAdapter()->fetchOne($count_sql);

			$data = new Zend_Dojo_Data('id', $items, 'name');
			$data->setMetadata('numRows', $count);

			$cache->save($data);
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
		//	'artist_view' => array('width' => 25, 'label' => '&nbsp;', 'get' => 'artist_view'),
		//	'artist_edit' => array('width' => 25, 'label' => '&nbsp;', 'get' => 'artist_edit'));

		foreach($pColumns as $k => $v) $columns[$k] = $v;

	//	$columns['artist_delete'] = array('width' => 25, 'label' => '&nbsp;', 'get' => 'artist_delete');


		return Zupal_Grid_Maker::querygrid($pID, $pStore_ID, $columns, 'id'); //, array('onRowClick' => 'artist_row_click'));
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
		$media =  Zupal_Module_Manager::getInstance()->get('media');
		$module_root = $media->directory();
		include $module_root . DS . join(DS, array('models', 'Zupal', 'Musicbrainz', 'Artist')) . DS . 'artists_grid_script.php';
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ render_store @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	 * @see Zupal_Grid_IGrid::render_store()
	 *
	 * @param unknown_type $pStore_ID
	 * @param unknown_type $pURL
	 */
	public function render_store($pStore_ID, $pURL) {
		ob_start();
		?>
<div dojoType="dojox.data.QueryReadStore"
    jsId="<?= $pStore_ID ?>"
    url="<?= $pURL ?>"
    doClientPaging="false" />
		<?
		return ob_get_clean();
	}

}

