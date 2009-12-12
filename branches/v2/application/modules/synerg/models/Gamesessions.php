<?

class Synerg_Model_Gamesessions
extends Game_Model_Gamesessions
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 */
    public function init () {
        $sgt = Synerg_Model_Gametypes::getInstance()->synergy_gametype();

        if(!$this->game_type):
            $this->game_type = $sgt->identity();
        endif;

        if (!($this->game_type == $sgt->identity())):
            throw new Exception(__METHOD__ . ': bad game type');
        endif;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ add_user @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pUser = NULL
     * @return <type>
     */
    public function add_user ($pUser = NULL) {
        parent::add_user($pUser);
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ active_esssion @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param  $pUser = NULL
     * @return
     */
    public function active_session ($pUser = NULL) {
        $sgt = Synerg_Model_Gametypes::getInstance()->synergy_gametype();
        $sgt_id = $sgt->identity();
        return parent::active_session($sgt_id, $pUser);
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_Instance = NULL;

    /**
     *
     * @param boolean $pReload
     * @return Synerg_Model_Gamesessions
     */
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }



}