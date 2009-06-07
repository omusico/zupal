<?

class Zupal_Media_MusicBrainz_Cache_Artists
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

			$backendOptions = array('file_name_prefix' => 'artist', 'hashed_directory_level' => 0, 'cache_dir' => self::cache_dir());

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
		if (is_null(self::$_cache_dir)):
			$dir = ZUPAL_PUBLIC_PATH . DS . join(DS, array('modules','media', 'musicbrainz', 'artists'));
			self::$_cache_dir = $dir;
		endif;

		if (!is_dir(self::$_cache_dir)):
			mkdir(self::$_cache_dir, 0775, TRUE);
		endif;

		return self::$_cache_dir;
	}
}