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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ target @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ *

    private $_target = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Ultimatum_Model_Ultgamegroups
     *
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
     *
    public function scanned_groups_for_group (Ultimatum_Model_Ultgamegroups $pGame_Group) {
        $pGame_Group = Zupal_Domain_Abstract::_as($pGame_Group, 'Ultimatum_Model_Ultgamegroups');
        $params = array(
            'player_group' => $pGame_Group->identity()
        );
        $scans = $this->find($params, 'scanned_at');

        $groups = array();

        $ggs = Ultimatum_Model_Ultgamegroups::getInstance();
        $game_id = $pGame_Group->get_game()->identity();
        $out = array();

        foreach($scans as $scan):
            $params = array('group_id' => $scan->target_group_id, 'game' => $game_id);
            /**
             * @var Ultimatum_Model_Ultgamegroups
             *
            $game_group =  $ggs->findOne($params);
            if ($game_group->player != $pGame_Group->player):
                $out[] = $game_group;
            endif;
        endforeach;

        return $out;
    } */

}

