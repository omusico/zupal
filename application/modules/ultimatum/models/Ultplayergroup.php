<?php

class Ultimatum_Model_Ultplayergroup extends Zupal_Domain_Abstract
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultplayergroup';
    }

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
            if ($pLoad_Fields && is_array($pLoad_Fields)):
                $out->set_fields($pLoad_Fields);
            endif;
            return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ for_player @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param Ultimatum_Model_Ultplayer $pPlayer
     * @return Ultimatum_Model_Ultplayergroup
     */
    public static function for_player (Ultimatum_Model_Ultplayer $pPlayer) {
        $params = array(
            'player' => $pPlayer->identity()
        );
        $pg = self::getInstance()->find($parms);

        return $pg;
    }
}

