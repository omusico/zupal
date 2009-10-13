<?php

class Pages_Model_Zupalpages extends Zupal_Domain_Abstract
{

    private static $_instance = 'zupal_pages';

    public function tableClass()
    {
        return 'Pages_Model_DbTable_Zupalpages';
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ publish_status @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_publish_status = NULL;
    function get_publish_status($pReload = FALSE) {
        if ($pReload || is_null($this->_publish_status)):
            $value = Pages_Model_Zupalpagestatuses::getInstance()->get($this->publish_status);
        // process
            $this->_publish_status = $value;
        endif;
        return $this->_publish_status;
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ atom @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param boolean $pAs_array
     * @return Model_Zupalatoms || array;
     */
    public function atom ($pAs_array = false) {
        if (!$this->atomic_id):
            $atom = Model_Zupalatoms::get_new();
            $this->atomic_id = $atom->atomic_id;
            $this->save();
            if ($pAs_array):
                return $atom->toArray();
            else:
                return $atom;
           endif;
        endif;
        return $this->atomic_id ? Model_Zupalatoms::latest($this->atomic_id, $pAs_array) : NULL;
    }
    
}

