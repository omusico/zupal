<?php

class Ultimatum_Model_Ultplayergroupknowledge extends Zupal_Domain_Abstract
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultplayergroupknowledge';
    }

/**
 *
 * @return Ultimatum_Model_Ultplayergroupknowledge
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
 * @return Ultimatum_Model_Ultplayergroupknowledge
 */
    public function get($pID = NULL, $pLoadFields = NULL)
    {
        $out = new self($pID);
            if ($pLoadFields && is_array($pLoadFields)):
                $out->set_fields($pLoadFields);
            endif;
            return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ link @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function link () {
        ob_start();
?>
<a href="/ultimatum/game/scanview/scan/<?= $this->identity() ?>"><?= $this->get_group() ?>(as of <?= $this->last_update ?>)</a>
<?
        return ob_get_clean();
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
        $size = $this->__get("{$pProperty}_size");
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
                Ultimatum_Model_Ultgroups::eff_factor($eff), FALSE
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
        $eff_factor = Ultimatum_Model_Ultgroups::eff_factor($eff);

        $size = $this->get_size($pProperty);
        if (is_null($size)):
            return $pString ? '(unknown)' : NULL;
        endif;

        return Ultimatum_Model_Ultgroups::effect($size, $eff_factor, $pString);
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
     * NOTE: this is the GROUP -- not the PLAYER GROUP 
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ for_player @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param variant $pPlayer
     * @return Ultimatum_Model_Ultplayergroupknowledge[]
     */
    public function for_player ($pPlayer) {
        $param = array(
            'player' => $this->_as($pPlayer, 'Ultimatum_Model_Ultplayers', TRUE)
        );
        return self::getInstance()->find($param, 'last_update DESC');
    }



/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ network_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function network_efficiency ($pString = FALSE) {
        return $this->get_effect(Ultimatum_Model_GroupProfileIF::PROP_NETWORK, $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ offense_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function offense_efficiency ($pString = FALSE) {
        return $this->get_effect(Ultimatum_Model_GroupProfileIF::PROP_OFFENSE, $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ defense_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function defense_efficiency ($pString = FALSE) {
        return $this->get_effect(Ultimatum_Model_GroupProfileIF::PROP_DEFENSE, $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ growth_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function growth_efficiency ($pString = FALSE) {
        return $this->get_effect(Ultimatum_Model_GroupProfileIF::PROP_GROWTH, $pString);
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ network_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function network_size ($pString = FALSE) {
        return $this->get_size(Ultimatum_Model_GroupProfileIF::PROP_NETWORK, $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ offense_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function offense_size ($pString = FALSE) {
        return $this->get_size(Ultimatum_Model_GroupProfileIF::PROP_OFFENSE, $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ defense_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function defense_size ($pString = FALSE) {
        return $this->get_size(Ultimatum_Model_GroupProfileIF::PROP_DEFENSE, $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ growth_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function growth_size ($pString = FALSE) {
        return $this->get_size(Ultimatum_Model_GroupProfileIF::PROP_GROWTH, $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ network_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function network_effect ($pString = FALSE) {
        return $this->get_effect(Ultimatum_Model_GroupProfileIF::PROP_NETWORK, $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ offense_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function offense_effect ($pString = FALSE) {
        return $this->get_effect(Ultimatum_Model_GroupProfileIF::PROP_OFFENSE, $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ defense_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function defense_effect ($pString = FALSE) {
        return $this->get_effect(Ultimatum_Model_GroupProfileIF::PROP_DEFENSE, $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ growth_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function growth_effect ($pString = FALSE) {
        return $this->get_effect(Ultimatum_Model_GroupProfileIF::PROP_GROWTH, $pString);
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ pending_orders @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return Ultimatum_Model_Ultplayergrouporders[]
     */
    public function pending_orders () {
        $orders = Ultimatum_Model_Ultplayergrouporders::getInstance();

        try {
            $sql = sprintf('SELECT po.id FROM %s po ', $orders->table()->tableName());
            $sql .= sprintf (' LEFT JOIN %s pg ON pg.id = po.player_group ', Ultimatum_Model_Ultplayergroups::getInstance()->table()->tableName());
            $sql .= sprintf(' WHERE po.target = %s ', $this->get_group()->identity());
            $sql .= sprintf(' AND pg.player = %s', $this->player);
            $sql .= ' AND po.active > 0;';
            error_log(__METHOD__ . ': finding orders; sql = ' . $sql);

            $out =  $orders->find_from_sql($sql, FALSE);
        } catch (Exception $e)
        {
            error_log(__METHOD__ . ': error on sql ' . $sql);
            $out = array();
        }
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ last_scans_for_player @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pPlayer_id
     * @return <type>
     */
    public function last_scans_for_player ($pPlayer_id, $pAs_array = FALSE) {
        if (is_array($pPlayer_id)):
            $scans = array();
            foreach($pPlayer_id as $pid):
                $scans = $scans + $this->last_scans_for_player($pid, $pAs_array);
            endforeach;
            return $scans;
        else:
            $sql = sprintf('SELECT group_id, id FROM %s WHERE player = ? ORDER BY last_update',
                $this->table()->tableName());
            $scan_ids = $this->table()->getAdapter()->fetchPairs($sql, $pPlayer_id);

/*
 * using the fact that the scans are returned in last_update order
 * to put the id of the most recent scan in the index for the group
 */

            if ($pAs_array):
                return $scan_ids; // returns an array where keys == scan ids and values == group ids
            else:
                $out = array();

                foreach($scan_ids as $id):
                    $out = $this->get($id);
                endforeach;

                return $out;
            endif;
        endif;
    }


}

