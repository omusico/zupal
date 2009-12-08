<?php

class Game_Model_Gamesessions extends Zupal_Domain_Abstract {

    private static $_Instance = null;

    protected $_soft_delete = TRUE;

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
        $out['plsyers'] = $this->player_count();
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ player_count @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return int
     */
    public function player_count () {
        $session_player_table = Game_Model_Gamesessionplayers::getInstance()->table();
        $sql = sprintf("SELECT count(ID) FROM %s WHERE user = ?", $session_player_table->tableName());
        return $session_player_table->getAdapter()->fetchOne($sql, array($this->user));
    }
    
}

