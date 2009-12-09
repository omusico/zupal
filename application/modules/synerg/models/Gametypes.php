<?

class Synerg_Model_Gametypes
extends Game_Model_Gametypes {
    const SYNERGY_NAME = 'SynerG';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ synergy_gametype @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_synergy_gametype = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Synerg_Model_Gametypes
     */
    public function synergy_gametype($pReload = FALSE) {
        if ($pReload || is_null(self::$_synergy_gametype)):
            self::$_synergy_gametype =  parent::gametype_by_name(self::SYNERGY_NAME);
        endif;
        return self::$_synergy_gametype;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_Instance = NULL;

/**
 *
 * @param boolean $pReload
 * @return Synerg_Model_Gametypes
 */
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

}