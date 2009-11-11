<?php

class Ultimatum_Model_Ultplayergroupsize extends Zupal_Domain_Abstract
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultplayergroupsize';
    }
/**
 *
 * @return Ultimatum_Model_Ultplayergroupsize
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
 * @return Ultimatum_Model_Ultplayergroupsize
 */
    public function get($pID = NULL, $pLoadFields = NULL)
    {
        $out = new self($pID);
        if ($pLoadFields && is_array($pLoadFields)):
            $out->set_fields($pLoadFields);
        endif;
        return $out;
    }


}

