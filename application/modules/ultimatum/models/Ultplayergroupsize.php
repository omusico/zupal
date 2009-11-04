<?php

class Ultimatum_Model_Ultplayergroupsize extends Zupal_Domain_Abstract
{

    private static $_instance = 'ult_player_group_size';

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultplayergroupsize';
    }

    public static function getInstance()
    {
        return 'Ultimatum_Model_DbTable_Ultplayergroupsize';
    }

    public function getInstance()
    {
        if ($pReload || is_null(self::$_Instance)):
            // process
                self::$_Instance = new self();
            endif;
            return self::$_Instance;
    }

    public function get()
    {
        $out = new self($pID);
            if ($pLoad_Fields && is_array($pLoad_Fields)):
                $out->set_fields($pLoad_Fields);
            endif;
            return $out;
    }


}

