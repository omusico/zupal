<?

class Zupal_Media_MusicBrainz_Cache
extends Zend_Cache
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_instance = NULL;

	/**
	 *
	 * @param boolean $pReload
	 * @return Zupal_Media_MusicBrainz_Cache
	 */
	public static function getInstance($pReload = FALSE)
	{
		if ($pReload || is_null(self::$_instance)):
		    $frontendOptions = array(
					'lifetime' => Zupal_Bootstrap::$registry->configuration->cache->lifetime,
				'automatic_serialization' => true
			);

			$backendOptions = array('cache_dir' => self::cache_dir());

			// getting a Zend_Cache_Core object
			$cache = Zend_Cache::factory('Core','File',$frontendOptions, $backendOptions);

			// process
			self::$_instance = $cache;
		endif;
		return self::$_instance;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ cache_dir @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_cache_dir = NULL;
	public static function cache_dir($pReload = FALSE)
	{
		if ($pReload || is_null(self::$_cache_dir)):
			$value = ZUPAL_ROOT_DIR . DS . Zupal_Bootstrap::$registry->configuration->cache->path . DS . 'modules/media/musicbrainz/artists_list';
			error_log(__METHOD__ . ': ' . $value);
			if (!is_dir($value)):
				mkdir($value, 775, TRUE);
			endif;
		// process
		self::$_cache_dir = $value;
		endif;
		return self::$_cache_dir;
	}
}