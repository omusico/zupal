<?php

class Zupal_Table_Media_Artists
extends Zupal_Table_Abstract
{
	protected $_id_field = 'artist_id';
	protected $_name = 'media_artists';
	
	const INSTALL_ARTISTS = '';

	public static function install()
	{
		Zupal_Database_Manager::get_adapter()->query(self::INSTALL_ARTISTS);
	}

}