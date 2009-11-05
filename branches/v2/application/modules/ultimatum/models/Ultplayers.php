<?php

class Ultimatum_Model_Ultplayers extends Zupal_Domain_Abstract
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultplayers';
    }
/**
 *
 * @return Ultimatum_Model_Ultplayers
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
 * @return Ultimatum_Model_Ultplayers
 */
    public function get($pID = 'NULL', $pLoadFields = 'NULL')
    {
        $out = new self($pID);
            if ($pLoad_Fields && is_array($pLoad_Fields)):
                $out->set_fields($pLoad_Fields);
            endif;
            return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ user_active_game @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param Model_Users $pUser = NULL
     * @return Ultimatum_Model_Ultplayers
     */
    public function user_active_player ($pUser = NULL) {
        if (!$pUser) $pUser = Model_Users::current_user();

        $params = array('user' => $pUser->identity(), 'active' => 1);

        $game = self::getInstance()->findOne($params);

        return $game;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ for_user @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return Ultimatum_Model_Ultplayers
     */
    public static function for_user_game ($pUser, $pGame, $pSpawn = TRUE) {
        if(is_numeric($pUser)):
            $pUser = Model_Users::getInstance()->get($pUser);
        endif;

        if (is_numeric($pGame)):
            $pGame = Ultimatum_Model_Ultgames::getInstance()->get($pGame);
        endif;
        //@TODO: better validation

        if (!$pUser || (!$pUser->is_saved())):
            throw new Exception(__METHOD__ . ': no user passed');
        endif;

        $params = array(
            'user' => $pUser->identity(),
            'game' => $pGame->identity()
        );

        if (!$player = self::getInstance()->findOne($params)):
            if ($pSpawn):
                $player = new Ultimatum_Model_Ultplayers();
                $player->set_fields($params);
                $player->save();
            endif;
        endif;

        return $player;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ for_user @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param ariant $pParam
     * @return Ultimatum_Model_Ultplayer
     */
    public static function for_user ($pUser) {
        if(is_numeric($pUser)):
            $pUser = Model_Users::getInstance()->get($pUser);
        endif;

        //@TODO: better validation

        if (!$pUser || (!$pUser->is_saved())):
            throw new Exception(__METHOD__ . ': no user passed');
        endif;

        $params = array(
            'user' => $pUser->identity()
        );

        $players = self::getInstance()->find($params, 'id');

        return $players;
    }


    public function activate () {

        foreach( self::for_user($this->get_user($pReload)) as $player):
            $player->active = $player->game == 0;
            $player->save();
        endforeach;

        $this->active = 1;
        $this->save();
        return $this;
        
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
    /**
     *
     * @param <type> $pReload
     * @return Ultimatum_Model_Ultgames
     */
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
        return $scan->full_scan($pGroup, $this);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __call @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pMethod, $pArgs
     * @return <type>
     */
    public function __call ($pMethod, $pArgs) {
        return $this->get_game()->{$pMethod}($pArgs);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ acquire @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pGroup
     * @return <type>
     */
    public function acquire ($pGroup) {
        if (!($pGroup = $this->_as($pGroup, 'Ultimatum_Model_Ultgroups', TRUE))):
            throw new Exception(__METHOD__ . ': bad gorup passed : ' . print_r($pGroup, 1));
        endif;

        $pg = new Ultimatum_Model_Ultplayergroup();
        $pg->game = $this->get_game()->identity();
        $pg->player = $this->identity();
        $pg->on_turn = $this->get_game()->turn(TRUE);
        $pg->save();
    }
}

