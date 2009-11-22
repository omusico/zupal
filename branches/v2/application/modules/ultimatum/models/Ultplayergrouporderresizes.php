<?php

class Ultimatum_Model_Ultplayergrouporderresizes extends Zupal_Domain_Abstract
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultplayergrouporderresizes';
    }

/**
 *
 * @return Ultimatum_Model_Ultplayergrouporderresizes
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ order @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_order = NULL;
    function order($pReload = FALSE) {
        if ($pReload || is_null($this->_order)):
            $value = Ultimatum_Model_Ultplayergrouporders::getInstance()->get($this->order_id);
        // process
            $this->_order = $value;
        endif;
        return $this->_order;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ player_group @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_player_group = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Ultimatum_Model_Ultplayergroups
     */
    function player_group($pReload = FALSE) {
        if ($pReload || is_null($this->_player_group)):
            $value = Ultimatum_Model_Ultplayergroups::getInstance()->get($this->order()->player_group);
        // process
            $this->_player_group = $value;
        endif;
        return $this->_player_group;
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ network_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function network_efficiency ($pString = FALSE){ 
        return $this->player_group()->network_efficiency($pString);
   }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ offense_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function offense_efficiency ($pString = FALSE){
        return $this->player_group()->offense_efficiency($pString);
   }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ defense_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function defense_efficiency ($pString = FALSE){
        return $this->player_group()->defense_efficiency($pString);
   }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ growth_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function growth_efficiency ($pString = FALSE){
        return $this->player_group()->growth_efficiency($pString);
   }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ network_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function network_size ($pString = FALSE){ 
        return $pString ? $this->network : $this->network - 100; 
   }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ offense_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function offense_size ($pString = FALSE){ 
        return $pString ? $this->offense : $this->offense - 100; 
   }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ defense_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function defense_size ($pString = FALSE){ 
        return $pString ? $this->defense : $this->defense - 100; 
   }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ growth_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function growth_size ($pString = FALSE){ 
        return $pString ? $this->growth : $this->growth - 100 ;
   }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ network_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function network_effect ($pString = FALSE){ 
        return Ultimatum_Model_Ultgroups::gp_effect($this, 'network', $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ offense_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function offense_effect ($pString = FALSE){
        return Ultimatum_Model_Ultgroups::gp_effect($this, 'offense', $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ defense_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function defense_effect ($pString = FALSE){
        return Ultimatum_Model_Ultgroups::gp_effect($this, 'defense', $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ growth_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function growth_effect ($pString = FALSE){
        return Ultimatum_Model_Ultgroups::gp_effect($this, 'growth', $pString);
    }

    
}

