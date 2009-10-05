<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * URL join table
 *
 * @author bingomanatee
 */

require_once ('Xtractlib/Domain/Abstract.php');

class Xtract_Model_UrlLinks
extends Xtractlib_Domain_Abstract
{

    public function tableClass()
    {
        return 'Xtract_Model_DbTable_UrlLinks';
    }

    public function get ($pID = null, $pLoad_Fields = NULL)
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
     * @param bool $pReload
     * @return Xtract_Model_UrlLinks
     */
    public static function getInstance($pReload = FALSE)
    {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ to_url @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_to_url = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Xtract_Model_Urls
     */
    function get_to_url($pReload = FALSE) {
        if ($pReload || is_null($this->_to_url)):
        // process
            $this->_to_url = Xtract_Model_Urls::get_url($this->to_url);
        endif;
        return $this->_to_url;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ from_url @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_from_url = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Xtract_Model_Urls
     */
    function get_from_url($pReload = FALSE) {
        if ($pReload || is_null($this->_from_url)):
        // process
            $this->_from_url = Xtract_Model_Urls::get_url($this->from_url);
        endif;
        return $this->_from_url;
    }
}
