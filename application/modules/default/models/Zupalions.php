<?php

class Model_Zupalions extends Zupal_Domain_Abstract
{

    private static $_instance = 'zupal_ions';

    public function tableClass()
    {
        return 'Model_DbTable_Zupalions';
    }

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

