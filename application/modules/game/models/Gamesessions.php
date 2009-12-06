<?php

class Game_Model_Gamesessions extends Zupal_Domain_Abstract {

    private static $_Instance = null;

    public function tableClass() {
        return 'Game_Model_DbTable_Gamesessions';
    }

    public static function getInstance() {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

    public function get($pID = NULL, $pLoadFields = NULL) {
        $out = new self($pID);
        if ($pLoadFields && is_array($pLoadFields)):
            $out->set_fields($pLoadFields);
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ jsonArray @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return array
     */
    public function toArray ($pExtend = TRUE) {
        $out = parent::toArray();
        if ($pExtend):
            $out['game'] = $this->game_type()->title;
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ game_type @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_game_type = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Game_Model_Gametypes
     */
    function game_type($pReload = FALSE) {
        if ($pReload || is_null($this->_game_type)):
        // process
            $this->_game_type = Game_Model_Gametypes::getInstance()->get($this->game_type);
        endif;
        return $this->_game_type;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ delete @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param  boolean $pTotal = FALSE
     * @return void
     */
    public function delete ($pTotal = FALSE) {
        if ($pTotal):
            return parent::delete();
        else:
            $this->active = 0;
            $this->save();
        endif;
    }

}
