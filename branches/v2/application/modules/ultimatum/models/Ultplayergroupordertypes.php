<?php

class Ultimatum_Model_Ultplayergroupordertypes extends Zupal_Domain_Abstract
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultplayergroupordertypes';
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
            if ($pLoadFields && is_array($pLoadFields)):
                $out->set_fields($pLoadFields);
            endif;
            return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ label @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function label () {
        return $this->name;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ find_by_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return Ultimatum_Model_Ultplayergroupordertypes
     */
    public static function find_by_name ($pName) {
        return self::getInstance()->findOne(array('name' => $pName));
    }

    const ORDER_SCAN = 'scan';

    public function __toString() {
        return $this->name;
    }
}

