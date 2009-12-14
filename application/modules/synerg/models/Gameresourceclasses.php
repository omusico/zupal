<?

class Synerg_Model_Gameresourceclasses
extends Game_Model_Gameresourceclasses {


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_Instance = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Synerg_Model_Gameresourceclasses
     */
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

    const CLASS_GROUPS = 'Entities';
    const CLASS_RESOURCES = "Physical Resources";
    const CLASS_METRICS = "Social Metrics";

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_by_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pName
     * @return Synerg_Model_Gameresourceclasses
     */
    public function get_by_name ($pName) {
        if (!array_key_exists($pName, self::$_grc)):
            $params = array('name' => $pName);
            self::$_grc[$pName] = self::getInstance()->findOne($params);
        endif;
        return self::$_grc[$pName];
    }

    private static $_grc = array();
}