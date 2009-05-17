function modules_identity(id, item)
{

	var g = dijit.byId('igrid_<?= $pID ?>_modules_node');

	return g.store.getValue(item, 'name');

}

function modules_view(id, item)
{
	if (!item) return this.defaultValue;

	var g = dijit.byId('igrid_<?= $pID ?>_modules_node');

//	id = g.store.getValue(item, 'id');

return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/admin/modules/view/name/' + modules_identity(id, item) + '">'
	+ '<?= Zupal_Image::icon('view')  ?></a>';
}


function modules_edit(id, item)
{
	if (!item) return this.defaultValue;

	var g = dijit.byId('igrid_<?= $pID ?>_modules_node');

//	id = g.store.getValue(item, 'id');

return '<a href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/admin/modules/edit/name/' +  modules_identity(id, item)  + '">'
	+ '<?= Zupal_Image::icon('edit')  ?></a>';
}

function format_bool(b)
{

	if (b) return '<div style="background-color: green; text-align: center; padding: 3px; color: #CFC; font-weight: bold">Y</div>';
	return '<div style="background-color: red; text-align: center; padding: 3px; color: #FCC; font-weight: bold">N</div>';
}

function format_bold(v){ return '<b>' + v + '</b>'; }
