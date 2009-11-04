<?php

class Ultimatum_Model_Ultplayer extends Zupal_Domain_Abstract
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultplayer';
    }
/**
 *
 * @return Ultimatum_Model_Ultplayer
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
 * @return Ultimatum_Model_Ultplayer
 */
    public function get($pID = 'NULL', $pLoadFields = 'NULL')
    {
        $out = new self($pID);
            if ($pLoad_Fields && is_array($pLoad_Fields)):
                $out->set_fields($pLoad_Fields);
            endif;
            return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ for_user @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param ariant $pParam
     * @return Ultimatum_Model_Ultplayer
     */
    public static function for_user_game ($pUser, $pGame, $pSpawn = TRUE) {
        if(is_numeric($pUser)):
            $pUser = Model_Users::getInstance()->get($pUser);
        endif;
        
        if (is_numeric($pGame)):
            $pGame = Ultimatum_Model_Ultgames::getInstance()->get($pGame);
        endif;
        

        if (!$pUser || (!$pUser->is_saved())):
            throw new Exception(__METHOD__ . ': no user passed');
        endif;

        $params = array(
            'user' => $pUser->identity(),
            'game' => $pGame->identity()
        );

        if (!$player = self::getInstance()->findOne($params)):
            if ($pSpawn):
                $player = new Ultimatum_Model_Ultplayer();
                $player->set_fields($params);
                $player->save();
            endif;
        endif;

        return $player;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ user @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_user = NULL;
    function get_user($pReload = FALSE) {
        if ($pReload || is_null($this->_user)):
        // process
            $this->_user = Model_Users::getInstance()->get($this->user);
        endif;
        return $this->_user;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ game @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_game = NULL;
    function get_game($pReload = FALSE) {
        if ($pReload || is_null($this->_game)):
        // process
            $this->_game = Ultimatum_Model_Ultgames::getInstance()->get($this->game);
        endif;
        return $this->_game;
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ groups @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * returns the players ownership wrapper for the groups they control.
     */
    public function groups () {
        return Ultimatum_Model_Ultplayergroup::for_player($this);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ full_scan_group @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param Ultimatum_Model_Groups $pGroup
     * @return scan
     */
    public function full_scan_group (Ultimatum_Model_Groups $pGroup) {

        $scan = new Ultimatum_Model_Ultplayergroupknowledge();
        $scan->set_group($pGroup);
        $scan->scan_full();
        $scan->save();
        return $scan;
    }
}

