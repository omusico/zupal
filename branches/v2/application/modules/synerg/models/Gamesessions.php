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
}