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
    public static function synergy_gametype($pReload = FALSE) {
        if ($pReload || is_null(self::$_synergy_gametype)):

            $params = array('name' => self::SYNERGY_NAME);
            $game = self::getInstance()->findOne($params);
            if (!$game):
                throw new Exception(__METHOD__ . 'No game named ' . self::SYNERGY_NAME . ' found.');
            endif;

            self::$_synergy_gametype = $game ;
        endif;
        return self::$_synergy_gametype;
    }
    
}