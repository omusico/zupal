<?php

class Ultimatum_Model_Ultgamegroups
extends Zupal_Domain_Abstract
implements Ultimatum_Model_GroupProfileIF
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultgamegroups';
    }
/**
 *
 * @return Ultimatum_Model_Ultgamegroups
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
 * @return Ultimatum_Model_Ultgamegroups
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
     * @return Ultimatum_Model_Ultgamegroups
     */
    public static function for_player (Ultimatum_Model_Ultplayer $pPlayer, $pRoot_only = FALSE) {
        $params = array('player' => $pPlayer->identity());

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
    
    public function set_player($pPlayer) {
//      $player = new Ultimatum_Model_Ultplayers();
        $pPlayer = Zupal_Domain_Abstract::_as($pPlayer, 'Ultimatum_Model_Ultplayers');

        if($this->player == $pPlayer->identity()):
            return;
        endif;

        if ($this->get_game()->identity() != $pPlayer->get_game()->identity()):
            throw new Exception(__METHOD__ . ': attempt to assign player from different game.');
        endif;

        $this->_player = $pPlayer;

        $this->player = $pPlayer->identity();
        $this->on_turn = $this->get_game()->turn();
        $this->save();
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ player @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function player () {
        return $this->get_player();
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ game @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_game = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Ultimatum_Model_Ultgames
     */
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
            return $this->get_group()->get_size($pProperty, $pString, $game);
        else:
            throw new Exception(__METHOD__. ': player group ' . $this->identity() . ' has no game');
        endif;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ sizes @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type>
     * @return <type>
     */
    public function sizes () {
        $game = $this->get_game();

        if ($game):
            return $this->get_group()->get_sizes($game);
        else:
            throw new Exception(__METHOD__. ': player group ' . $this->identity() . ' has no game');
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
            $this->$prop = $this->get_size($prop);
        endforeach;

        
        parent::save();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ start_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param int $pTarget_size
     */
    public function start_size ($pTarget_size = 30, $pFactor = 3) {
        $size = $pTarget_size % $pFactor;
        $fraction = floor($pTarget_size / $pFactor);
        for ($i = 0; $i < $pFactor; ++$i):
            $size += rand(0, $fraction);
        endfor;

        $this->add_size($size);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ add_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function add_size ($pSize = 0) {
        $pSize = (int) $pSize;
        $game_id = $this->get_game()->identity();
        if (!$pSize || $game_id):
            return;
        endif;

        $size = new Ultimatum_Model_Ultplayergroupsize();
        $size->group_id = $this->group_id();
        $size->size = $pSize;
        $size->game = $game_id;
        $size->save();
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
            $pgo = Ultimatum_Model_Ultplayergrouporders::getInstance();
            $this->_orders = $pgo->find($params, 'series');
        endif;
        return $this->_orders;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ pending_order @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return Ultimatum_Model_Ultplayergrouporders
     */
    public function pending_order () {

        $po = Ultimatum_Model_Ultplayergrouporders::getInstance();

        $select = $po->table()->select()
            ->where('active = ?', 1)
            ->where('player_group = ?', $this->identity())
            ->where('status = ?', Ultimatum_Model_Ultplayergrouporders::STATUS_PENDIONG)
            ->orWhere('status = ?', Ultimatum_Model_Ultplayergrouporders::STATUS_EXECUTING);

        return $po->findOne($select, 'series');
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ pending_orders @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return array
     */
    public function pending_orders () {

        $po = Ultimatum_Model_Ultplayergrouporders::getInstance();

        $select = $po->table()->select()
            ->where('active = ?', 1)
            ->where('player_group = ?', $this->identity())
            ->where('status = ?', Ultimatum_Model_Ultplayergrouporders::STATUS_PENDIONG)
            ->orWhere('status = ?', Ultimatum_Model_Ultplayergrouporders::STATUS_EXECUTING);

        return $po->find($select, 'series');
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ player_group_ids @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *  Returns the group IDs (NOT player group ids) 
     * of the groups owned by a given player (or players).
     *
     * @param int $pPlayer_id
     * @return int[]
     */
    public function player_group_ids ($pPlayer_id) {
        $sql = sprintf('SELECT DISTINCT group_id FROM %s ', $this->table()->tableName());
        if (is_array($pPlayer_id)):
            $sql .= sprintf('WHERE player IN (%s)', join(',', $pPlayer_id));
            return $this->table()->getAdapter()->fetchCol($sql);
        else:
            $sql .= 'WHERE player = ?';
            return $this->table()->getAdapter()->fetchCol($sql, $pPlayer_id);
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ group_for_game @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * This method is a bit more "expansive" than as_game_group
     * as it allows a game group object to autospawn if $pGreat = TRUE.
     * @param  $pGroup
     * @return Ultimatum_Model_Ultgamegroups
     */
    public function group_for_game ($pGroup, $pGame = NULL, $pCreate = FALSE) {
        $pGroup = Zupal_Domain_Abstract::_as($pGroup, 'Ultimatum_Model_Ultgroups', TRUE);
        $pGame = $pGame ? Zupal_Domain_Abstract::_as($pGame, 'Ultimatum_Model_Ultgames', TRUE) : Ultimatum_Model_Ultgames::getInstance()->get_active_id();
        
        $params = array('group' => $pGroup, 'game' => $pGame);
        
        $gfg = $this->findOne($params);
        
        if (!$gfg && $pCreate):
            $gfg = new self();
            $gfg->group = $pGroup;
            $gfg->game  = $pGame;
            $gfg->save();
        endif;
        
        return $gfg;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ as_group @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * This methos presues any integer passed is an identity of a GAME GROUP
     * not a group.
     */
    public static function as_game_group ($pGameGroup, $pAs_ID = FALSE) {
        if (!$pGameGroup instanceof Ultimatum_Model_Ultgamegroups):
            if ($pGameGroup instanceof Ultimatum_Model_Ultgroups):
                $pGameGroup = self::getInstance()->group_for_game($pGameGroup);
            elseif (is_numeric($pGameGroup)):
                $pGameGroup = new Ultimatum_Model_Ultgamegroups($pGameGroup);
                if (!$pGameGroup->isSaved()):
                    return NULL;
                endif;
            endif;
        endif;

        if ($pAs_ID):
            return $pGameGroup->identity();
        else:
            return $pGameGroup;
        endif;
    }
}

