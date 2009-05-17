function artist_identity(id, item)
{

	var g = dijit.byId('<?= $pID ?>');

	return g.store.getValue(item, 'node_id');

}

function artist_view(id, item)
{
	if (!item) return this.defaultValue;

	var g = dijit.byId('<?= $pID ?>');


return '<a href="<?= ZUPAL_BASEURL ?>/media/artists/view/name/' + artist_identity(id, item) + '">'
	+ '<?= Zupal_Image::icon('view')  ?></a>';
}


function artist_delete(id, item)
{
	if (!item) return this.defaultValue;

	var g = dijit.byId('<?= $pID ?>');


return '<a href="<?= ZUPAL_BASEURL ?>/media/artists/delete/name/' +  artist_identity(id, item)  + '">'
	+ '<?= Zupal_Image::icon('x')  ?></a>';
}

function artist_edit(id, item)
{
	if (!item) return this.defaultValue;

	var g = dijit.byId('<?= $pID ?>');


return '<a href="<?= ZUPAL_BASEURL ?>/media/artists/edit/name/' +  artist_identity(id, item)  + '">'
	+ '<?= Zupal_Image::icon('edit')  ?></a>';
}

function format_bool(b)
{

	if (b) return '<div style="background-color: green; text-align: center; padding: 3px; color: #CFC; font-weight: bold">Y</div>';
	return '<div style="background-color: red; text-align: center; padding: 3px; color: #FCC; font-weight: bold">N</div>';
}

function format_bold(v){ return '<b>' + v + '</b>'; }
