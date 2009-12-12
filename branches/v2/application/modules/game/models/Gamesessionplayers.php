<?php

class Game_Model_Gamesessionplayers extends Zupal_Domain_Abstract
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Game_Model_DbTable_Gamesessionplayers';
    }

/**
 *
 * @return Game_Model_Gamesessionplayers
 */
    public static function getInstance()
    {
        if ($pReload || is_null(self::$_Instance)):
            // process
                self::$_Instance = new self();
            endif;
            return self::$_Instance;
    }
/**
 *
 * @param scalar $pID
 * @param array $pLoadFields
 * @return Game_Model_Gamesessionplayers
 */
    public function get($pID = NULL, $pLoadFields = NULL)
    {
        $out = new self($pID);
            if ($pLoadFields && is_array($pLoadFields)):
                $out->set_fields($pLoadFields);
            endif;
            return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ session @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_session = NULL;
    function session($pReload = FALSE) {
        if ($pReload || is_null($this->_session)):
        // process
            $this->_session = Game_Model_Gamesessions::getInstance()->get($this->game_session);
        endif;
        return $this->_session;
    }

}

