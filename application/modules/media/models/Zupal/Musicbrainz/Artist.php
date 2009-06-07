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
	public function render_data(array $pParams, $pStart = 0, $pRows = 100, $pSort = 'name')
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

			$cache->save($key, $data);
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


		return Zupal_Grid_Maker::querygrid($pID, $pStore_ID, $columns, 'id', array('onRowClick' => 'artist_row_click')); //, array('onRowClick' => 'artist_row_click'));
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
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ type @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type>
	* @return <type>
	*/
	public function type ()
	{
		switch($this->type):
			case 1:
				return 'person';
			case 2:
				return 'group';
			default:
				return 'other';
		endswitch;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ is_artist @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function is_person ()
	{
		return $this->type == 1;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ groups @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	private $_groups = NULL;
	/**
	*
	* @param int $pDepth = 1 -- currently ignored
	* @return Zupal_Musicbrainz_Artist[]
	*/
	function groups($pDepth = 1, $pReload = FALSE)
	{
		if (!$this->isSaved()) return NULL;

		if ($pReload || is_null($this->_groups)):
			$cache = Zupal_Media_MusicBrainz_Cache::getInstance();

			$key = 'artist_groups_' . $this->identity();

			if ((!$cache->test($key)) || $pReload):
				$sql = 'SELECT laa.link1 AS id, laa.link_type AS link_type' .
				' FROM l_artist_artist laa LEFT JOIN artist a ON a.id = link1 ' .
				' WHERE (link0 = ?) and (a.type = 2) ORDER BY a.begindate';
				error_log(__METHOD__ . ': ' . $sql);
				$data = $this->table()->getAdapter()->fetchAssoc($sql, array($this->identity()));
				$cache->save($data, $key);
			endif;
			$data = $cache->load($key);
			$groups = array();
			if ($data):
				foreach($data as $keys):
					extract($keys);
					$artist = new Zupal_Musicbrainz_Artist($id);
					$link = new Zupal_Musicbrainz_Lt_Artist_Artist($link_type);
					$group = new stdClass();
					$group->artist = $artist;
					$group->type = $link;

					$groups[] = $group;
				endforeach;
			endif;
			$this->_groups = $groups;
		endif;
		return $this->_groups;
	}

	/*
	 *
SELECT a1.name, lat.name, a2.name
FROM l_artist_artist laa
INNER JOIN `lt_artist_artist` lat ON laa.link_type = lat.id
INNER JOIN artist a1 ON a1.id = link0
INNER JOIN artist a2 ON a2.id = link1
LIMIT 0 , 30
	 */

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ people @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_people = NULL;
	function people($pDepth = 1, $pReload = FALSE)
	{
		if (!$this->isSaved()) return NULL;

		if ($pReload || is_null($this->_people)):
			$cache = Zupal_Media_MusicBrainz_Cache::getInstance();

			$key = 'artist_people_' . $this->identity();

			if ((!$cache->test($key)) || $pReload):
				$sql = 'SELECT laa.link0 AS id, laa.link_type AS link_type' .
				' FROM l_artist_artist laa LEFT JOIN artist a ON a.id = link0 ' .
				' WHERE (link1 = ?) and (a.type = 1) ORDER BY a.begindate';
				error_log(__METHOD__ . ': ' . $sql);
				$data = $this->table()->getAdapter()->fetchAssoc($sql, array($this->identity()));
				$cache->save($data, $key);
			endif;
			$data = $cache->load($key);
			$people = array();
			if ($data):
				foreach($data as $keys):
					extract($keys);
					$artist = new Zupal_Musicbrainz_Artist($id);
					$link = new Zupal_Musicbrainz_Lt_Artist_Artist($link_type);
					$group = new stdClass();
					$group->artist = $artist;
					$group->type = $link;

					$people[] = $group;
				endforeach;
			endif;
			$this->_people = $people;
		endif;
		return $this->_people;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ is_group @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function is_group ()
	{
		return $this->type == 2;
	}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ json @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return string
	*/
	public function json ($pReload = FALSE)
	{
		$key = str_replace('-', '_', $this->gid);
		$ac = Zupal_Media_MusicBrainz_Cache_Artists::getInstance();

		if ($pReload || !$ac->test($key)):
			$ac->save(Zend_Json::encode($this->json_data()), $key);
		endif;

		return $ac->load($key);
	}

	public function json_data($pBrief = FALSE)
	{
		
		$data = array();
		if ($pBrief):
			$data['id'] = $this->identity();
			$data['name'] = $this->name;
			$data['type'] = $this->type();

			if ($this->is_group()):
			$data['people'] = array();
				foreach($this->people() as $person_data):
					if ($person_data->type->is_type(1)): // musical
						$data['people'][] = $person_data->artist->json_data(1);
					endif;
				endforeach;
			endif;
		else:

		$data['artist'] = $this->toArray();

		$data['groups'] = array();

		if ($this->is_person()):
			foreach($this->groups() as $group):
				$data['groups'][] = array_merge(
					$group->artist->json_data(1),
					array('type_text' => $group->type->linkphrase(TRUE))
				);
			endforeach;
		endif;

		$data['people'] = array();

		foreach($this->people() as $person):
			$data['people'][] = array_merge(
					$person->artist->json_data(1),
					array('type_name' => $person->type->linkphrase())
				);
		endforeach;
		endif;
		return $data;
	}


}

