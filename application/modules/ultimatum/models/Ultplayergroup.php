<?php

class Ultimatum_Model_Ultplayergroup extends Zupal_Domain_Abstract
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultplayergroup';
    }

    public static function getInstance()
    {
        if ($pReload || is_null(self::$_Instance)):
            // process
                self::$_Instance = new self();
            endif;
            return self::$_Instance;
    }

    public function get($pID = NULL, $pLoadFields = NULL)
    {
        $out = new self($pID);
            if ($pLoad_Fields && is_array($pLoad_Fields)):
                $out->set_fields($pLoad_Fields);
            endif;
            return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ for_player @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param Ultimatum_Model_Ultplayer $pPlayer
     * @return Ultimatum_Model_Ultplayergroup
     */
    public static function for_player (Ultimatum_Model_Ultplayer $pPlayer) {
        $params = array(
            'player' => $pPlayer->identity()
        );
        $pg = self::getInstance()->find($parms);

        return $pg;
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ player @@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * Note - this is the SCANNING player, not the owner of the group.
     * @return Ultimatum_Model_Ultplayer;
     */
    
    public function get_player() { return Ultimatum_Model_Ultplayer::getInstance()->get($this->player); }
    
    public function set_player($pValue) { 
        if (is_numeric($pValue)):
            $player = Ultimatum_Model_Ultplayer::getInstance()->get($pValue); 
        elseif ($pValue instanceof Ultimatum_Model_Ultplayer):
            $player = $pValue;
        else:
            throw new Exception(__METHOD__ . ': bad value passed: ' . print_r($pValue, 1));
        endif;
        
        $this->_player = $player;
        $this->player = $player->identity();
        $this->game = $player->game;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ game @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_game = NULL;
    function get_game($pReload = FALSE) {
        if ($pReload || is_null($this->_game)):
        // process
            $this->_game = Ultimatum_Model_Ultplayer::getInstance()->get($this->game); ;
        endif;
        return $this->_game;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pProperty
     * @return int
     */
    public function get_size ($pProperty) {
        $game = $this->get_game();

        if ($game):
            return $this->get_group()->get_size($game, $pProperty);
        else:
            return 0;
        endif;
    }

}

