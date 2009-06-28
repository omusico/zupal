
	function user_identity(id, item)
	{

		var g = dijit.byId('<?= $pID ?>');
		return g.store.getValue(item, 'person_id');

	}

	function user_view(id, item)
	{
		if (!item) return this.defaultValue;

		var g = dijit.byId('<?= $pID ?>');

		return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl()
	?>/people/users/view/id/' + user_identity(id, item) + '">'
		+ '<?= Zupal_Image::icon('view')  ?></a>';
	}


	function user_edit(id, item)
	{
		if (!item) return this.defaultValue;

		var g = dijit.byId('<?= $pID ?>');

		return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl()
	?>/people/users/edit/id/' +  user_identity(id, item)  + '">'
		+ '<?= Zupal_Image::icon('edit')  ?></a>';
	}


	function user_delete(id, item)
	{
		if (!item) return this.defaultValue;

		var g = dijit.byId('<?= $pID ?>');

		return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl()
	?>/people/usersq` nj1453/delete/id/' +  user_identity(id, item)  + '">'
		+ '<?= Zupal_Image::icon('x')  ?></a>';
	}
