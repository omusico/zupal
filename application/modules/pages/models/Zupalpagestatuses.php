<?php

class Pages_Model_Zupalpagestatuses extends Zupal_Domain_Abstract
{

    public function tableClass()
    {
        return 'Pages_Model_DbTable_Zupalpagestatuses';
    }

    public function get($pID = 'NULL', $pLoad_Fields = NULL)
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
     * @return Pages_Model_Zupalpages
     */
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }
}

