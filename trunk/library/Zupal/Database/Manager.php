<?

class Zupal_Database_Manager
{
	private static $_adapter = NULL;

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_adapter @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pScrub
 * @return Zend_Db_Adapter_Abstract
 */
	public static function get_adapter($pScrub = FALSE)
	{
		if (is_null(self::$_adapter) || $pScrub):
			$db =  Zend_Registry::getInstance()->configuration->database;
			if(!$db->adapter){ $db->adapter = 'mysqli'; }

			self::$_adapter = Zend_Db::factory($db);
		endif;
		return self::$_adapter;
	}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public static function init ($pScrub = FALSE){
		Zend_Db_Table_Abstract::setDefaultAdapter(self::get_adapter($pScrub));
	}

}
