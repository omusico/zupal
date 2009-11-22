<?php

class Ultimatum_Model_Ultplayergroupordertypes extends Zupal_Domain_Abstract
{

    const TARGET_TYPE_SELF = 'self';
    const TARGET_TYPE_OTHER = 'other';
    const TARGET_TYPE_BOTH = 'both';
    const TARGET_TYPE_NONE = 'none';


    private static $_Instance = null;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultplayergroupordertypes';
    }
    
/**
 *
 * @return Ultimatum_Model_Ultplayergroupordertypes
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
 * @return Ultimatum_Model_Ultplayergroupordertypes
 */
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
        return $this->title;
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
        return $this->title;
    }
}

