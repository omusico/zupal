function roles_identity(id, item)
{

	var g = dijit.byId('<?= $pID ?>');

	return g.store.getValue(item, 'id');

}

function roles_view(id, item)
{
	if (!item) return this.defaultValue;

	var g = dijit.byId('<?= $pID ?>');

	return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/admin/acl/roleview/id/' + roles_identity(id, item) + '">'
	+ '<?= Zupal_Image::icon('view')  ?></a>';
}

function roles_edit(id, item)
{
	if (!item) return this.defaultValue;

	var g = dijit.byId('<?= $pID ?>');

//	id = g.store.getValue(item, 'id');

return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/admin/acl/roleedit/id/' +  roles_identity(id, item)  + '">'
	+ '<?= Zupal_Image::icon('edit')  ?></a>';
}


function roles_delete(id, item)
{
	if (!item) return this.defaultValue;

	var g = dijit.byId('<?= $pID ?>');

//	id = g.store.getValue(item, 'id');

return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/admin/acl/roledelete/id/' +  roles_identity(id, item)  + '">'
	+ '<?= Zupal_Image::icon('x')  ?></a>';
}

