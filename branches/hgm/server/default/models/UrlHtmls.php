<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Urls
 *
 * @author bingomanatee
 */


class Xtract_Model_UrlHtmls
extends Xtractlib_Domain_Abstract
{

    public function tableClass()
    {
        return 'Xtract_Model_DbTable_UrlHtmls';
    }

    public function get ($pID = null, $pLoad_Fields = NULL) {
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
     * @return Xtract_Model_UrlHtmls
     */
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ scan @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function scan () {
        return Xtractlib_Html_Scan::scan($this);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ url @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_url = NULL;
    function url($pReload = FALSE) {
        if ($pReload || is_null($this->_url)):
        // process
            $this->_url = Xtract_Model_Urls::getInstance()->get($this->in_url);
        endif;
        return $this->_url;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_html @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @param Xtract_Model_UrlHtmls | int $pHTML
     * @return Xtract_Model_UrlHtmls
     */
    public static function get_html ($pHTML) {
        Xtractlib_Log::message(__METHOD__);
        if ($pHTML instanceof Xtract_Model_UrlHtmls):
            Xtractlib_Log::message(__METHOD__ . ': returning  object id = ' . $pHTML->identity());
            return $pHTML;
        elseif (is_numeric($pHTML)):
            Xtractlib_Log::message(__METHOD__ . ': getting object for id ' . $pHTML);
            return self::getInstance()->get($pHTML);
        else:
            throw new Exception(__METHOD__ . ': cannot get ' . print_r($pHTML, 1));
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ link @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param Xtract_Model_Url | int | string$pFrom
 * @param Xtract_Model_Url | int | string $pTo
 * @param Xtract_Model_UrlHtmls | int $pHtml
 * @return Xtract_Model_UrlLinks
 */
    public static function make_link ($pFrom, $pTo, $pHtml) {
        Xtractlib_Html_Link::link_to($pFrom, $pTo, $pHtml);
    }
}