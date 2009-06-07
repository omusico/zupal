
function artist_row_click(e)
{
	// alert(e.rowIndex);

	g = dijit.byId('<?= $pID ?>');

	item = g.getItem(e.rowIndex).i;

	alert(item.id);

	id = item.id;
	document.location = '<?= ZUPAL_BASEURL ?>/media/musicbrainz/artist/id/' + id;
}
