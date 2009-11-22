<?php

class Ultimatum_Model_Ultplayergroups
extends Zupal_Domain_Abstract
implements Ultimatum_Model_GroupProfileIF
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultplayergroups';
    }
/**
 *
 * @return Ultimatum_Model_Ultplayergroups
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
 * @return Ultimatum_Model_Ultplayergroups
 */
    public function get($pID = NULL, $pLoadFields = NULL)
    {
        $out = new self($pID);
        if ($pLoadFields && is_array($pLoadFields)):
            $out->set_fields($pLoadFields);
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
        
        $pg = self::getInstance()->find($params, 'on_turn');

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
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ player @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function player () {
        return $this->get_player();
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

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_group_id @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return int
     */
    public function group_id () {
        return $this->group_id;
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pProperty
     * @return scalar
     */
    public function get_size ($pProperty, $pString = FALSE) {
        $game = $this->get_game();

        if ($game):
            return $this->get_group()->get_size($pProperty, $game, $pString);
        else:
            throw new Exception(__METHOD__. ': player group ' . $this->identity() . ' has no string');
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pProperty
     * @param boolean $pString
     * @return scalar
     */
    public function get_efficiency ($pProperty, $pString = FALSE) {
        return $this->get_group()->get_efficiency($pProperty, $pString);
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pProperty
     * @return scalar
     */
    public function get_effect ($pProperty, $pString) {
        $game = $this->get_game();

        if ($game):
            return $this->get_group()->get_effect($pProperty, $pString, $game);
        else:
            throw new Exception(__METHOD__. ': player group ' . $this->identity() . ' has no string');
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


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ GroupProfileIF @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ network_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function network_efficiency ($pString = FALSE)
    {
        return $this->get_efficiency(Ultimatum_Model_GroupProfileIF::PROP_NETWORK, $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ offense_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function offense_efficiency ($pString = FALSE)
    {
        return $this->get_efficiency(Ultimatum_Model_GroupProfileIF::PROP_OFFENSE, $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ defense_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function defense_efficiency ($pString = FALSE)
    {
        return $this->get_efficiency(Ultimatum_Model_GroupProfileIF::PROP_DEFENSE, $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ growth_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function growth_efficiency ($pString = FALSE)
    {
        return $this->get_efficiency(Ultimatum_Model_GroupProfileIF::PROP_GROWTH, $pString);
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ network_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function network_size ($pString = FALSE)
    {
        return $this->get_size(Ultimatum_Model_GroupProfileIF::PROP_NETWORK, $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ offense_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function offense_size ($pString = FALSE)
    {
        return $this->get_size(Ultimatum_Model_GroupProfileIF::PROP_OFFENSE, $pString);
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ defense_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function defense_size ($pString = FALSE)
    {
        return $this->get_size(Ultimatum_Model_GroupProfileIF::PROP_DEFENSE, $pString);
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ growth_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function growth_size ($pString = FALSE)
    {
        return $this->get_size(Ultimatum_Model_GroupProfileIF::PROP_GROWTH, $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ network_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function network_effect ($pString = FALSE){
        return $this->get_effect(Ultimatum_Model_GroupProfileIF::PROP_NETWORK, $pAsString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ offense_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function offense_effect ($pString = FALSE){
        return $this->get_effect(Ultimatum_Model_GroupProfileIF::PROP_OFFENSE, $pAsString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ defense_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function defense_effect ($pString = FALSE){
        return $this->get_effect(Ultimatum_Model_GroupProfileIF::PROP_DEFENSE, $pAsString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ strength @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function strength ($pString) {
        $strength = $this->network_size() + $this->growth_size() + $this->offense_size() + $this->defense_size();

        return $pString ? 400 + $strength : $strength;
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ growth_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function growth_effect ($pString = FALSE){
        return $this->get_effect(Ultimatum_Model_GroupProfileIF::PROP_GROWTH, $pAsString);
    }

    public function __toString() {
        return $this->get_group() . ' - controlled by ' . $this->get_player();
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ orders @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_orders = NULL;
    function get_orders($pReload = FALSE) {
        if ($pReload || is_null($this->_orders)):
            $params = array('player_group' => $this->identity());
            $pgo = Ultimatum_Model_Ultplayergrouporder::getInstance();
            $this->_orders = $pgo->find($params, 'start_turn');
        endif;
        return $this->_orders;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ pending_orders @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return array
     */
    public function pending_orders () {
        $orders = $this->get_orders();
        $out = array();
        foreach($orders as $o):
            if ($o->active):
                $out[] = $o;
            endif;
        endforeach;
        return $out;
    }
}

