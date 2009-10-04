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
     * @param bool $pReload
     * @return Xtract_Model_UrlLinks
     */
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ from @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return Xtract_Model_Urls
     */
    public function from () {
        if (!$this->from) return FALSE;
        return Xtract_Model_Urls::getInstance()->get($this->from);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ to @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return Xtract_Model_Urls
     */
    public function to () {
        if (!$this->to) return FALSE;
        return Xtract_Model_Urls::getInstance()->get($this->to);
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ link @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param Xtract_Model_Url | int | string$pFrom
 * @param Xtract_Model_Url | int | string $pTo
 * @param Xtract_Model_UrlHtmls | int $pHtml
 * @return Xtract_Model_UrlLinks
 */
    public static function link ($pFrom, $pTo, $pHtml) {
        error_log(__METHOD__);
        
        $from = Xtract_Model_Urls::get_url($pFrom);
        $to   = Xtract_Model_Urls::get_url($pTo);
        $html = Xtract_Model_UrlHtmls::get_html($pHtml);

        error_log(__METHOD__ . ': params:');
        $params = array(
            'from_url'      => $from->identity(),
            'to_url'        => $to->identity(),
            'found_in_html' => $html->identity()
        );

        error_log(__METHOD__ . ': linking ' . print_r($params, 1));
        
        if (!($link = self::getInstance()->findOne($params))):
            $link = self::getInstance()->get(NULL, $params);
        endif;

        return $link; 
    }
    
}