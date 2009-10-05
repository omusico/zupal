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

class Xtract_Model_Urls
extends Xtractlib_Domain_Abstract
{

    public function tableClass()
    {
        return 'Xtract_Model_DbTable_Urls';
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
     * @return Xtract_Model_Urls
     */     
    public static function get_url ($pURL, $pHTML = NULL) {
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

        return $url;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ scan @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     */
    public function parse ($content = NULL) {
        error_log(__METHOD__);
        if (!($content || ($content = file_get_contents($this->url)))):
            throw new Xtractlib_Exception('Cannot get file', $this->url);
        endif;
        
        error_log(__METHOD__ . ': scanning ' . $this->url . ' = ' . substr($content, 0, 100));
        $this->save();
        error_log(__METHOD__ . '************');

        $ht = Xtract_Model_UrlHtmls::getInstance();
        $html = $ht->findOne(array('in_url' => $this->identity()));
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
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __set @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
 /**
  *
  * @param string $pField
  * @param scalar $pValue
  * @return void
  */
    public function __set ($pField, $pValue) {
        switch($pField):
            case 'url':
                return $this->set_url($pValue);
            break;
            default:
                parent::__set($pField, $pValue);
        endswitch;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_value @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pUrl
     * @return string
     */
    public function set_url ($pUrl) {
        $url_data = parse_url($pUrl);
        if (!$url_data):
            $url = "http://$pUrl";
            $url_data = parse_url($url);
            if (!$url_data):
                throw new Xtractlib_Exception('cannot parse url', $pUrl);
            endif;
            $pUrl = $url;
        endif;

        parent::__set('url', $pUrl);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function parse_url () {
        error_log(__METHOD__);
        $uri = Zend_Uri::factory($this->url);
        error_log(print_r($uri, 1));
        $this->path = $uri->getPath();
        $this->query = $uri->getQuery();
        $this->set_domain($uri->getHost());

        parent::save();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function save () {
        $this->parse_url();
        parent::save();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_domain = NULL;
    function get_domain($pReload = FALSE) {
        if ($pReload || is_null($this->_domain)):
            $this->parse_url();
        endif;
        return $this->_domain;
    }

    public function set_domain($pValue) 
    {
        $this->_domain = Xtract_Model_UrlDomains::get_domain($pValue);
        $this->domain = $this->_domain->identity();
    }

}