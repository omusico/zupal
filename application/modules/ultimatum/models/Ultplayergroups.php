<?php

class Ultimatum_Model_Ultplayergroups extends Zupal_Domain_Abstract
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultplayergroups';
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
     * @return Ultimatum_Model_Ultplayergroups
     */
    public static function for_player (Ultimatum_Model_Ultplayer $pPlayer, $pRoot_only = FALSE) {
        $params = array(
            'player' => $pPlayer->identity()
        );
        if ($pRoot_only):
            $params['controlling_group'] = 0;
        endif;
        
        $pg = self::getInstance()->find($parms, 'on_turn');

        return $pg;
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ player @@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * Note - this is the SCANNING player, not the owner of the group.
     * @return Ultimatum_Model_Ultplayer;
     */
    
    public function get_player() { return Ultimatum_Model_Ultplayers::getInstance()->get($this->player); }
    
    public function set_player($pValue) { 
        if (is_numeric($pValue)):
            $player = Ultimatum_Model_Ultplayers::getInstance()->get($pValue); 
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
            $this->_game = Ultimatum_Model_Ultgames::getInstance()->get($this->game); ;
        endif;
        return $this->_game;
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ group @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_group = NULL;
    /**
     *
     * @param <type> $pReload
     * @return Ultimatum_Model_Ultgroups
     */
    function get_group($pReload = FALSE) {
        if ($pReload || is_null($this->_group)):
        // process
            $this->_group = Ultimatum_Model_Ultgroups::getInstance()->get($this->group_id);
        endif;
        return $this->_group;
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_power @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pProperty
     * @return <type>
     */
    public function get_power ($pProperty) {
        $game = $this->get_game();

        if ($game):
            return $this->get_group()->get_power($game, $pProperty);
        else:
            return 0;
        endif;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function save () {
        foreach(Ultimatum_Model_Ultgroups::$_properties as $prop):
            $this->$prop = $this->get_power($prop);
        endforeach;
        parent::save();
    }
}

