<?php

class Ultimatum_Model_Ultgamegroupscans
extends Zupal_Domain_Abstract
{

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultgamegroupscans';
    }

    private static $_Instance = null;
/**
 *
 * @return Ultimatum_Model_Ultgamegroupscans
 */
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
            if ($pLoadFields && is_array($pLoadFields)):
                $out->set_fields($pLoadFields);
            endif;
            return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ target @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_target = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Ultimatum_Model_Ultgamegroups
     */
    function get_target($pReload = FALSE) {
        if ($pReload || is_null($this->_target)):
        // process
            $this->_target = Ultimatum_Model_Ultgamegroups::getInstance()->get($this->target);
        endif;
        return $this->_target;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ scans_for_group @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param  $pGame_Group
     * @return array
     */
    public function scanned_groups_for_player (Ultimatum_Model_Ultplayers $pPlayer) {
        $pPlayer = $this->_as($pPlayer, 'Ultimatum_Model_Ultplayers' , TRUE);

        $pParams = array('player' => $pPlayer, 'active' => 1);
        $scans = $this->find($pParams);

        $out = array();
        // double check to ensure you aren't returning groups the player OWNS.
        //@TODO: can be done in SQL. 
        foreach($scans as $scan):
            $gg = $scan->game_group();
            if (!($gg->player == $pPlayer)):
                $out[] = $gg;
            endif;
        endforeach;

        return $out;
    } 

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ game_group @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_game_group = NULL;
    function game_group($pReload = FALSE) {
        if ($pReload || is_null($this->_game_group)):
            $value = Ultimatum_Model_Ultgamegroups::as_game_group($this->target_group_id);
        // process
            $this->_game_group = $value;
        endif;
        return $this->_game_group;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ group @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_group = NULL;
    function get_group($pReload = FALSE) {
        if ($pReload || is_null($this->_group)):
        // process
            $this->_group = Ultimatum_Model_Ultgroups::getInstance()->get($this->target_group_id);
        endif;
        return $this->_group;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __call @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function __call($pName, $pParams) {
        $gg = $this->game_group();
        return call_user_func_array(array($gg, $pName), $pParams);
    }

    public function __get($pField) {
        return $this->game_group()->$pField;
    }

    public function __set($pField, $pValue) {
        return $this->game_group()->$pField = $pValue;
    }
}

