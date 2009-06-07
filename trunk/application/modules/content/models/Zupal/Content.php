<?php
/**
 * Note -- this content is NOT a versioned model!
 * @TODO write a versioned flavor. 
 */

class Zupal_Content extends Zupal_Node_Abstract
implements Zupal_Content_IContent, Zupal_Grid_IGrid
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ versions @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function versions ()
	{
		return $this->domain_find(
			array(
				$this->node_field() =>	$this->nodeId()
			),
			$this->table()->idField(),
			TRUE
		);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ grid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function render_grid(Zend_View $pView, $pID, $pStore_ID, array $pColumns)
	{

		Zupal_Grid_Maker::prep_view($pView);

		$columns = array(
			'' => array('width' => 25, 'label' => '&nbsp;', 'get' => 'content_view'),
			'content_edit' => array('width' => 25, 'label' => '&nbsp;', 'get' => 'content_edit'));

		foreach($pColumns as $k => $v) $columns[$k] = $v;

		$columns['content_delete'] = array('width' => 25, 'label' => '&nbsp;', 'get' => 'content_delete');

		return Zupal_Grid_Maker::grid( $pID, $pStore_ID, $columns);

	}

	public function render_data(array $pParams, $pStart = 0, $pRows = 30, $pSort = NULL)
	{
		$cache = Zupal_Bootstrap::$registry->cache;

		if (!$cache->test('content_data')):
			$select = $this->_select($pParams, $pSort);
			$items = $this->table()->getAdapter()->fetchAll($select);
			$cache->save('content_data', new Zend_Dojo_Data($this->table()->idField(), $items, $pSort));
		endif;

		return $cache->load('content_data');

	}

	public function render_script($pID, array $pParams = NULL)
	{
?>

	function content_identity(id, item)
	{

		var g = dijit.byId('<?= $pID ?>');

		return g.store.getValue(item, 'id');

	}

	function content_view(id, item)
	{
		if (!item) return '';

		var g = dijit.byId('<?= $pID ?>');

		content_id = content_identity(id, item);
		url = '<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/content/admin/view/id/';
		icon = '<?= Zupal_Image::icon('view')  ?>';
	return '<a href="' + url + content_id + '">' + icon + '</a>';
	}


	function content_edit(id, item)
	{
		if (!item) return this.defaultValue;

		var g = dijit.byId('<?= $pID ?>');

	//	id = g.store.getValue(item, 'id');

	return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/content/admin/edit/id/' +  content_identity(id, item)  + '">'
		+ '<?= Zupal_Image::icon('edit')  ?></a>';
	}


	function content_delete(id, item)
	{
		if (!item) return this.defaultValue;

		var g = dijit.byId('<?= $pID ?>');

	//	id = g.store.getValue(item, 'id');

	return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/content/admin/delete/id/' +  content_identity(id, item)  + '">'
		+ '<?= Zupal_Image::icon('x')  ?></a>';
	}

<?
	}

	public function render_store($pStore_ID, $pURL)
	{
		return Zupal_Grid_Maker::store($pStore_ID, $pURL);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param  $pID
	* @return Zupal_Content
	*/
	public function get ($pID)
	{
		return new Zupal_Content($pID);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ tableClass @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	* @return string
	*/
	public function tableClass ()
	{
		return preg_replace('~^Zupal_~', 'Zupal_Table_', get_class($this));
	}

/* @@@@@@@@@@@@@@@@@@@@@@ CONTENT INTERFACE @@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * A string labelf or the content.
	 * @return string
	 */
	public function title()
	{
		return $this->title;
	}

	/**
	 * A shorter title, for menus and lists.
	 */
	public function shortTitle()
	{
		return $this->title();
	}

	/**
	 * Content, in HTML
	 */
	public function text()
	{
		return $this->text;
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
		return $this->raw_text;
	}

	/**
	 * determines whether the content is within the publish dates and is public.
	 * @return boolean
	 */
	public function isPublished()
	{
		return TRUE;
		//@TODO: use publish dates to reflect cycle
	}

	/**
	 * the expected or past date to expose the content.
	 * Can be left blank for content still in review.
	 *
	 * @return GMT timestamp
	 */
	public function publishDate()
	{
		return $this->publish_date;
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
		return $this->unpublish_date;
	}

	/**
	 * The domain object of the author.
	 * @return Zupal_Content_Domain
	 */
	public function author()
	{
		@TODO;
	}

	public function authorId()
	{
		return $this->author_id;
	}

	/**
	 * Indicates this content item has been approved for general view.
	 * if public is not public, only authorized people can view it -- in general, the admins,
	 * but ultimately determined by the role system.
	 * @return boolean
	 */
	public function isPublic ()
	{
		return $this->is_public;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ link @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return string
	*/

	const LINK_TEMPLATE = '<a href="%s">%s</a>';

	public function link ()
	{
		$url = DS . Zend_Controller_Front::getInstance()->getBaseUrl() . join(DS,
			array('content', 'item', 'view', 'node', $this->nodeId())
		);
		return sprintf(self::LINK_TEMPLATE, $url, $this->title());
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_instance = NULL;

/**
 *
 * @param bool $pReload
 * @return Zupal_Content
 */
	public static function getInstance($pReload = FALSE)
	{
		if ($pReload || is_null(self::$_instance)){
		// process
			self::$_instance = new Zupal_Content(Zupal_Domain_Abstract::STUB);
		}
		return self::$_instance ;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ select @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pParam
	* @return <type>
	*/
	public function select ($pParam, $pSort = NULL)
	{
		return $out;
	}
}