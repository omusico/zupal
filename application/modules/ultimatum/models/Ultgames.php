<?php

class Ultimatum_Model_Ultgames extends Zupal_Domain_Abstract
{

    private static $_Instance = 'ult_games';

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultgames';
    }

/**
 *
 * @return Ultimatum_Model_Ultgames
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
 * @return Ultimatum_Model_Ultgames
 */
    public function get($pID = NULL, $pLoad_Fields = NULL)
    {
        $out = new self($pID);
            if ($pLoad_Fields && is_array($pLoad_Fields)):
                $out->set_fields($pLoad_Fields);
            endif;
            return $out;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ title @@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * @return string;
     */

    public function get_title() { return $this->title ? $this->title : 'Ultimatum Game ' . $this->identity(); }

    public function set_title($pValue) { $this->title = $pValue; $this->save();}

}

