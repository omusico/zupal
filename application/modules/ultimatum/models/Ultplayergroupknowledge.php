<?php

class Ultimatum_Model_Ultplayergroupknowledge extends Zupal_Domain_Abstract
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultplayergroupknowledge';
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ full_scan @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function full_scan ($pGroup = NULL, $pPlayer = NULL) {
        if ($pGroup) $this->set_group($pGroup);
        if ($pPlayer) $this->set_player($Player);
        
        $game = $this->get_game();

        foreach(Ultimatum_Model_Ultgroups::$_properties as $field):
            $scan_field = "group_$field";
            $this->$scan_field = $game->$field;
        endforeach;

        $params = array(
            'game' => $game->identity(),
            'group' => $this->group
        );

        if ($game_group = Ultimatum_Model_Ultplayergroup::getInstance()->find($params)):
            $this->group_player = $game_group->player;
            foreach(Ultimatum_Model_Ultgroups::$_properties as $field):
                $scan_field = "{$field}_size";
                $this->$scan_field = $game_group->get_size($field);
            endforeach;
        endif;
        
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ group @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function set_group($pValue) {
        if ($pValue instanceof Ultimatum_Model_DbTable_Ultgroups):
            $pValue = $pValue->identity();
        elseif(!is_numeric($pValue)):
            throw new Exception(__METHOD__ . ': bad value passed : ' . print_r($pValue, 1));
        endif;

        $this->group = $pValue;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ group @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_group = NULL;
    function get_group($pReload = FALSE) {
        if ($pReload || is_null($this->_group)):
        // process
            $this->_group = Ultimatum_Model_DbTable_Ultgroups::getInstance()->get($this->group);
        endif;
        return $this->_group;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ player @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function set_player($pValue) {
        if ($pValue instanceof Ultimatum_Model_Ultplayers):
            $pValue = $pValue->identity();
        elseif(!is_numeric($pValue)):
            throw new Exception(__METHOD__ . ': bad value passed : ' . print_r($pValue, 1));
        endif;

        $this->player = $pValue;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ player @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_player = NULL;

    /**
     * @return Ultimatum_Model_Ultplayer;
     */
    function get_player($pReload = FALSE) {
        if ($pReload || is_null($this->_player)):
        // process
            $this->_player = Ultimatum_Model_Ultplayer::getInstance()->get($this->player);
        endif;
        return $this->_player;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ game @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     * @param boolean $pReload
     * @return Ultimatum_Model_Ultgames
     */
    function get_game($pReload = FALSE) {
        if (!$player = $this->get_player()):
            throw new exception(__METHOD__ . ': attempt to get game without player ');
        endif;

        $value = $player->get_game();
        return $value;
    }
    
}

