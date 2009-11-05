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
        if ($pPlayer) $this->set_player($pPlayer);
        
        $game = $this->get_game();
        $group = $this->get_group();

        foreach(Ultimatum_Model_Ultgroups::$_properties as $field):
            $scan_field = "group_$field";
            $this->$scan_field = $group->$field;

            $size_field = "{$field}_size";
            $this->$size_field = $group->get_size($game, $field);
        endforeach;

        $this->save();
        return $this;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pProperty
     * @return int
     */
    public function get_size ($pProperty, $pString = FALSE) {
        $size = $this->__get("{$Property}_size");
        if (is_null($size)):
            return $pString ? '(unknown)' : NULL;
        else:
            return $pString ? $size + 100 : $size;
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pProperty
     * @return int | string
     */
    public function get_efficiency ($pProperty, $pString = FALSE) {
        $eff = $this->__get("group_$pProperty");

        if (is_null($eff)):
            return $pString ? '(unknown)' : NULL;
        else:
            return ($pString) ? Zupal_Util_Format::percent(
                Ultimatum_Model_Ultgroups::eff_factor($eff)
            ) : $eff;
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pProperty
     * @return <type>
     */
    public function get_effect ($pProperty, $pString = FALSE) {

        $eff = $this->get_efficiency($pProperty);
        if (is_null($eff)):
            return $pString ? '(unknown)' : NULL;
        endif;

        $size = $this->get_size($pProperty)
        if (is_null($size)):
            return $pString ? '(unknown)' : NULL;
        endif

        $size += 100;
        $size *= Ultimatum_Model_Ultgroups::eff_factor($eff);

        return $pString ? number_format($size) : $size;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ group @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function set_group($pValue) {
        if ($pValue instanceof Ultimatum_Model_Ultgroups):
            $pValue = $pValue->identity();
        elseif(!is_numeric($pValue)):
            throw new Exception(__METHOD__ . ': bad value passed : ' . print_r($pValue, 1));
        endif;

        $this->group_id = $pValue;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ group @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_group = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Ultimatum_Model_Ultgroups
     */
    function get_group($pReload = FALSE) {
        if ($pReload || is_null($this->_group)):
        // process
            $this->_group = Ultimatum_Model_Ultgroups::getInstance()->get($this->group_id);
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
     * @return Ultimatum_Model_Ultplayers;
     */
    function get_player($pReload = FALSE) {
        if ($pReload || is_null($this->_player)):
        // process
            $this->_player = Ultimatum_Model_Ultplayers::getInstance()->get($this->player);
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

