function artist_identity(id, item)
{

	var g = dijit.byId('<?= $pID ?>');

	return g.store.getValue(item, 'node_id');

}

function artist_row_click(e)
{
	// alert(e.rowIndex);

	g = dijit.byId('<?= $pID ?>');

	item = g.getItem(e.rowIndex);

	// alert(item.performs_as);

	f = dojo.byId('artist_detail');

	f.performs_as.value = item.performs_as;
	f.node_id.value = item.node_id;
	f.person_name_first.value = item.person_name_first;
	f.person_name_last.value = item.person_name_last;
	f.person_born.value = item.person_born;

	i = dojo.byId('person_gender-' + item.person_gender);
	if (i) i.checked = true;

	t = dojo.byId('type-' + item.type);
	if (t) t.checked = true;
}

function artist_view(id, item)
{
	if (!item) return this.defaultValue;

	var g = dijit.byId('<?= $pID ?>');


return '<a href="<?= ZUPAL_BASEURL ?>/media/artists/view/node_id/' + artist_identity(id, item) + '">'
	+ '<?= Zupal_Image::icon('view')  ?></a>';
}


function artist_delete(id, item)
{
	if (!item) return this.defaultValue;

	var g = dijit.byId('<?= $pID ?>');


return '<a href="<?= ZUPAL_BASEURL ?>/media/artists/delete/node_id/' +  artist_identity(id, item)  + '">'
	+ '<?= Zupal_Image::icon('x')  ?></a>';
}

function artist_edit(id, item)
{
	if (!item) return this.defaultValue;

	var g = dijit.byId('<?= $pID ?>');


return '<a href="<?= ZUPAL_BASEURL ?>/media/artists/edit/node_id/' +  artist_identity(id, item)  + '">'
	+ '<?= Zupal_Image::icon('edit')  ?></a>';
}

function format_bool(b)
{

	if (b) return '<div style="background-color: green; text-align: center; padding: 3px; color: #CFC; font-weight: bold">Y</div>';
	return '<div style="background-color: red; text-align: center; padding: 3px; color: #FCC; font-weight: bold">N</div>';
}

function format_bold(v){ return '<b>' + v + '</b>'; }
