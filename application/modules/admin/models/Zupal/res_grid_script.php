function res_identity(id, item)
{

	var g = dijit.byId('<?= $pID ?>');

	return g.store.getValue(item, 'id');

}

function res_view(id, item)
{
	if (!item) return this.defaultValue;

	var g = dijit.byId('<?= $pID ?>');

	return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/admin/acl/resview/id/' + res_identity(id, item) + '">'
	+ '<?= Zupal_Image::icon('view')  ?></a>';
}

function res_edit(id, item)
{
	if (!item) return this.defaultValue;

	var g = dijit.byId('<?= $pID ?>');

//	id = g.store.getValue(item, 'id');

return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/admin/acl/resedit/id/' +  res_identity(id, item)  + '">'
	+ '<?= Zupal_Image::icon('edit')  ?></a>';
}


function res_delete(id, item)
{
	if (!item) return this.defaultValue;

	var g = dijit.byId('<?= $pID ?>');

//	id = g.store.getValue(item, 'id');

return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/admin/acl/resdelete/id/' +  res_identity(id, item)  + '">'
	+ '<?= Zupal_Image::icon('x')  ?></a>';
}

