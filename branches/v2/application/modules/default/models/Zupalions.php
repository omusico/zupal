<?php

class Model_Zupalions extends Zupal_Domain_Abstract
{

    private static $_instance = 'zupal_ions';

    public function tableClass()
    {
        return 'Model_DbTable_Zupalions';
    }
/**
 *
 * @param string $pID
 * @param array $pLoadFields
 * @return Model_Zupalions
 */
    public function get($pID = 'NULL', $pLoadFields = 'NULL')
    {
        $out = new self($pID);
            if ($pLoadFields && is_array($pLoadFields)):
                $out->set_fields($pLoadFields);
            endif;
            return $out;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    private static $_Instance = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Model_Zupalions
     */
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_atomic_id @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return int
     */
    public function get_atomic_id () {
        return $this->atomic_id;
    }
}

