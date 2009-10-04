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

require_once ('Xtractlib/Domain/Abstract.php');

class Xtract_Model_UrlDomains
extends Xtractlib_Domain_Abstract
{

    public function tableClass()
    {
        return 'Xtract_Model_DbTable_UrlDomains';
    }

    public function get ($pID = null, $pLoad_Fields = NULL) {
        $out = new self($pID);
        if ($pLoad_Fields && is_array($pLoad_Fields)):
            $out->set_fields($pLoad_Fields);
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_url @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pURL
     * @return Xtractlib_Domain_Abstract
     */
     
    public static function get_url ($pURL) {
        if (is_numeric($pURL)):
            return self::getInstance()->get($pURL);
        elseif ($pURL instanceof Xtract_Model_Urls):
            return $pURL;
        endif;

        $url = self::getInstance()->findOne(array('url' => $pURL));

        if (!$url):
            $url = new self();
            $url->url = $pURL;
        endif;
        $url->save();

        return $url;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ scan @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     */
    public function parse () {
        error_log(__METHOD__);
        if (!($content = file_get_contents($this->url))):
            throw new Xtractlib_Exception('Cannot get file', $this->url);
        endif;
        error_log(__METHOD__ . ': scanning ' . $this->url . ' = ' . substr($content, 0, 100));
        $this->save();
        error_log(__METHOD__ . '************');
        
        $html = Xtract_Model_UrlHtmls::getInstance()->findOne(array('in_url' => $this->identity()));
        if (!$html):
            $html = new Xtract_Model_UrlHtmls();
        endif;

        $html->in_url  = $this->identity();
        $html->html = $content;
        $html->save();
        $html->scan();
        return $content;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_Instance = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Xtract_Model_UrlDomains
     */
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_domain @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param int | string | Xtract_Model_UrlDomains $pDomain
     * @return Xtract_Model_UrlDomains
     */
    public static function get_domain ($pDomain) {
        if ($pDomain instanceof Xtract_Model_UrlDomains):
            return $pDomain;
        elseif (is_numeric($pDomain)):
            return self::getInstance()->get($pDomain);
        else:
            $pDomain = strtolower($pDomain);
            
            if (!($domain = self::getInstance()->findOne(array('host' => $pDomain)))):
                $domain = new self();
                $domain->host = $pDomain;
                $domain->save();
            endif;

            return $domain;
        endif;
    }
    
}