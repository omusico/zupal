<?php

class Ultimatum_Model_Ultgroups
extends Model_Zupalatomdomain
implements Ultimatum_Model_GroupProfileIF
{

    public static $_properties = array('offense', 'defense', 'network', 'growth');

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultgroups';
    }
/**
 *
 * @param int $pID
 * @param array $pLoadFields
 * @return Model_Zupalatomdomain
 */
    public function get($pID = 'NULL', $pLoadFields = 'NULL')
    {
        $out = new self($pID);
        if ($pLoadFields && is_array($pLoadFields)):
            $out->set_fields($pLoadFields);
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ eff_factor @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * returns a value of around 1.0
     *
     * @param int $pOffset
     * @return float
     */
    public static function eff_factor ($pOffset) {
        $pOffset = (int) $pOffset;
        return (10 * (10 + $pOffset))/100;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_Instance = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Ultimatum_Model_Ultgroups
     */
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ for_atomic_id @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param int $pAtomic_id
     * @return Pages_Model_Zupalpages
     */
    public function for_atom_id ($pAtomic_id) {
        return $this->findOne(array('atomic_id' => $pAtomic_id), 'id DESC');
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ atomic_id @@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * @return class;
     */

    public function get_atomic_id() { return $this->atomic_id; }

    public function set_atomic_id($pValue) { $this->atomic_id = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_atom @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param boolean $pReload = FALSE
     * @return Model_Zupalatoms
     */
    public function get_atom ($pReload = FALSE) {
        $out = parent::get_atom($pReload);
        if (! $out->get_model_class() != ($class = get_class($this))):
            $out->set_model_class($class);
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ randomize @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param int $pWalks = 10
     * @return int
     */
    public function randomize ($pWalks = 4) {
        $keys = array('offense', 'defense', 'growth', 'network');
        $this->offense = $this->defense = $this->growth = $this->network = 0;
        for ($i = 0; $i < $pWalks; $i += 2):
            $rand_pairs = Zupal_Util_Array::random_set($keys, 2);
            ++$this->{$rand_pairs[0]};
            --$this->{$rand_pairs[1]};
        endfor;

        $this->set_title($this->_random_name());
        $this->set_status('published');
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _random_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */

    private static $_alphabet = array('A','B','C','D','E','F',
        'G','H', 'I', 'J', 'K', 'L', 'M', 'N','O', 'P', 'Q', 'R',
        'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

    public function _random_name () {
        $letter_count = rand(1,2) + rand(1,2) + rand(1,2);
        $letters = Zupal_Util_Array::random(self::$_alphabet, $letter_count);
        return join('. ', $letters) . '.';
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return scalar
     */
    public function get_size ($pProperty, $pGame = NULL, $pString) {
        if (is_null($pGame)):
            if (!($pGame = Ultimatum_Model_Ultgames::user_active_game())):
                throw new Exception(__METHOD__ . ': no game');
            endif;
            $pGame = $pGame->identity();
        endif;
        $pGame = $this->_as($pGame, 'Ultimatum_Model_Ultgames', TRUE);

        $sizes = Ultimatum_Model_Ultplayergroupsize::getInstance();

        $select = $sizes->table()->select()
            ->from($sizes->table()->tableName(), array('SUM(size) as total_size') );

        $params = array(
            'group_id' => $this->identity(),
            'game' => $pGame);

        foreach($params as $f => $v):
            $select->where("$f = ?", $v);
        endforeach;
        
        $sql = $select->assemble();

        $size = (int) $sizes->table()->getAdapter()->fetchOne($sql);
        return $pString ? 100 + $size : $size;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param $pGame, $pProperty
     * @return float
     */
    public function get_effect ($pProperty, $pAsString = FALSE, $pGame = NULL ) {
       $size = $this->get_size($pProperty, $pGame);
       $eff = $this->get_efficiency($pProperty);
       return self::effect($size, $eff, $pAsString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return float | string
     */
    public static function effect ($pSize, $pEff = 0, $pString = FALSE) {
        if (is_null($pSize) || is_null($pEff)):
            return $pString ? '(unknown)' : NULL;
        endif;

        $pSize += 100;
        $eff_factor = self::eff_factor($pEff);
        
        $pSize *= $eff_factor;

        return $pString ? number_format($pSize, 0) : $pSize;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ gp_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param Ultimatum_Model_GroupProfileIF $pProfile
     * @return number | string
     */
    public function gp_effect (Ultimatum_Model_GroupProfileIF $pProfile, $pFactor, $pString = FALSE) {
        switch(strtolower($pFactor)):
            case 'network':
                    $size = $pProfile->network_size();
                    $efficiency = $pProfile->network_efficiency();
                break;

            case 'growth':
                    $size = $pProfile->growth_size();
                    $efficiency = $pProfile->growth_efficiency();
                    break;

            case 'offense':
                    $size = $pProfile->offense_size();
                    $efficiency = $pProfile->offense_efficiency();
                    break;

            case 'defense':
                    $size = $pProfile->defense_size();
                    $efficiency = $pProfile->defense_efficiency();
                    break;

        endswitch;

        return self::effect($size, $efficiency, $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pProperty
     * @return int | string
     */

    public function get_efficiency ($pProperty, $pString = FALSE) {
        $eff = $this->__get($pProperty);

        if ($pString):
            return Zupal_Util_Format::percent(self::eff_factor($eff), FALSE);
        else:
            return $eff;
        endif;
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
        return $this->get_efficiency(Ultimatum_Model_GroupProfileIF::PROP_OFFENSE,  $pString);
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
        return $this->get_size(Ultimatum_Model_GroupProfileIF::PROP_NETWORK, NULL, $pString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ offense_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function offense_size ($pString = FALSE)
    {
        return $this->get_size(Ultimatum_Model_GroupProfileIF::PROP_OFFENSE, NULL, $pString);
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ defense_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function defense_size ($pString = FALSE)
    {
        return $this->get_size(Ultimatum_Model_GroupProfileIF::PROP_DEFENSE, NULL, $pString);
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ growth_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function growth_size ($pString = FALSE)
    {
        return $this->get_size(Ultimatum_Model_GroupProfileIF::PROP_GROWTH, NULL, $pString);
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ growth_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function growth_effect ($pString = FALSE){
        return $this->get_effect(Ultimatum_Model_GroupProfileIF::PROP_GROWTH, $pAsString);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __toString @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function __toString () {
        return 'group ' . $this->get_title();
    }
}

