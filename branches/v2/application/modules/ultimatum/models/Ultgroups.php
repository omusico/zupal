<?php

class Ultimatum_Model_Ultgroups
extends Model_Zupalatomdomain
{


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


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_Instance = NULL;
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
        $out->model_class = 'Model_Zupalatomdomain';
        return $out;
    }
}

