<?php

class Ultimatum_Model_Ultgroups
extends Model_Zupalatomdomain
{

    public static $_properties = array('offense', 'defense', 'network', 'growth');

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultgroups';
    }
/**
 *
 * @param int $pID
 * @param array $pLoad_Fields
 * @return Model_Zupalatomdomain
 */
    public function get($pID = 'NULL', $pLoad_Fields = 'NULL')
    {
        $out = new self($pID);
        if ($pLoad_Fields && is_array($pLoad_Fields)):
            $out->set_fields($pLoad_Fields);
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ eff_factor @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param int $pOffset
     * @return float
     */
    public static function eff_factor ($pOffset) {
        $pOffset = (int) $pOffset;
        return (10 * (10 + $pOffset))/100;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ power @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return float | string
     */
    public static function power ($pSize, $pEff = 0, $pString = FALSE) {
        if (is_null($pSize) || is_null($pEff)):
            return $pString ? '(unknown)' : NULL;
        endif;

        $pSize += 100;
        $pSize *= self::eff_factor($pEff);

        return $pString ? number_format($pSize, 1) : $pSize;
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
        return $this->findOne(array('atomic_id' => $pAtomic_id));
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
        if (! $out->model_class):
            $out->model_class = 'Model_Zupalatomdomain';
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ offense @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pAs_String = FALSE
     * @return <type>
     */
    public function offense ($pAs_String = FALSE) {
        return $pAs_String ? Zupal_Util_Format::percent($this->offense*10) : $this->offense/10;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ defense @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pAs_String = FALSE
     * @return <type>
     */
    public function defense ($pAs_String = FALSE) {
        return $pAs_String ? Zupal_Util_Format::percent($this->defense*10) : $this->defense/10;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ network @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pAs_String = FALSE
     * @return <type>
     */
    public function network ($pAs_String = FALSE) {
        return $pAs_String ? Zupal_Util_Format::percent($this->network*10) : $this->network/10;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ growth @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pAs_String = FALSE
     * @return <type>
     */
    public function growth ($pAs_String = FALSE) {
        return $pAs_String ? Zupal_Util_Format::percent($this->growth*10) : $this->growth/10;
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
     * @param <type> $pGame, $pProperty
     * @return <type>
     */
    public function get_size ($pGame, $pProperty) {
        if ($pGame instanceof Ultimatum_Model_Ultgames):
            $pGame = $pGame->identity();
        elseif (!is_numeric($pGame)):
            throw new Exception(__METHOD__ . ' : bad value passed for game: ' . print_r($pGame, 1));
        endif;

        $sizes = Ultimatum_Model_Ultplayergroupsize::getInstance();

        $select = $sizes->table()->select()
            ->from($sizes->table()->tableName(),
                array(
                    'SUM(size) as total_size'
                    )
            );

        $params = array(
            'group_id' => $this->identity(),
            'game' => $pGame,
            'activity' => $pProperty);

        foreach($params as $f => $v):
            $select->where("$f = ?", $v);
        endforeach;
        
        $sql = $select->assemble();

        return (int) $sizes->table()->getAdapter()->fetchOne($sql);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_power @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param $pGame, $pProperty
     * @return float
     */
    public function get_power ($pGame, $pProperty, $pAsString = FALSE) {
       if (!$pGame = $this->_as($pGame, 'Ultimatum_Model_Ultgames', FALSE)):
            throw new Exception(__METHOD__ . ': bad game passed: ' . print_r($pGame, 1));
       endif;

       $size = $this->get_size($pGame, $pProperty);
       return self::power($size, $pProperty, $pAsString);
    }
}

