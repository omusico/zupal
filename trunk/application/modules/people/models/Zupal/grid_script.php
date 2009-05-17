<script language="javascript">

	function people_identity(id, item)
	{

		var g = dijit.byId('igrid_<?= $pID ?>_people_node');

		return g.store.getValue(item, 'person_id');

	}

	function people_view(id, item)
	{
		if (!item) return this.defaultValue;

		var g = dijit.byId('igrid_<?= $pID ?>_people_node');

	//	id = g.store.getValue(item, 'id');

	return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/people/item/view/id/' + people_identity(id, item) + '">'
		+ '<?= Zupal_Image::icon('view')  ?></a>';
	}


	function people_edit(id, item)
	{
		if (!item) return this.defaultValue;

		var g = dijit.byId('igrid_<?= $pID ?>_people_node');

	//	id = g.store.getValue(item, 'id');

	return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/people/item/edit/id/' +  people_identity(id, item)  + '">'
		+ '<?= Zupal_Image::icon('edit')  ?></a>';
	}


	function people_delete(id, item)
	{
		if (!item) return this.defaultValue;

		var g = dijit.byId('igrid_<?= $pID ?>_people_node');

	//	id = g.store.getValue(item, 'id');

	return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/people/item/delete/id/' +  people_identity(id, item)  + '">'
		+ '<?= Zupal_Image::icon('x')  ?></a>';
	}

</script>