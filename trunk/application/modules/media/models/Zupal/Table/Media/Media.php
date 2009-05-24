<?php

class Zupal_Table_Media_Media
extends Zupal_Table_Abstract
{
	protected $_id_field = 'media_id';
	protected $_name = 'media_media';
	
	const INSTALL_MEDIA = '';

	public static function install()
	{
		Zupal_Database_Manager::get_adapter()->query(self::INSTALL_MEDIA);
	}

}