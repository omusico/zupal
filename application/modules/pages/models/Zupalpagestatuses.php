<?php

class Pages_Model_Zupalpagestatuses extends Zupal_Domain_Abstract
{

    public function tableClass()
    {
        return 'Pages_Model_DbTable_Zupalpagestatuses';
    }

    public function get($pID = 'NULL', $pLoadFields = NULL)
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

