<?

class Zupal_Database_Manager {
    private static $_adapter = NULL;

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_adapter @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param boolean $pScrub
     * @return Zend_Db_Adapter_Abstract
     */
    public static function get_adapter($pScrub = FALSE) {
        $config = Zend_Registry::getInstance();

        if (Zend_Db_Table::getDefaultAdapter()):
            return Zend_Db_Table::getDefaultAdapter();
        endif;

        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        if ($bootstrap):
            $options = $bootstrap->getOptions();
        else:
            $options = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini');
        endif;

        if (is_null(self::$_adapter) || $pScrub):
            $db =  $options->resource->db;
            if(!$db->adapter) { $db->adapter = 'mysqli'; }

            self::$_adapter = Zend_Db::factory($db);
            if (!self::$_adapter):
                throw new Exception('Cannot retrieve database adapter');
            endif;
        endif;
        return self::$_adapter;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public static function init ($pScrub = FALSE) {
        Zend_Db_Table_Abstract::setDefaultAdapter(self::get_adapter($pScrub));
    }

}
