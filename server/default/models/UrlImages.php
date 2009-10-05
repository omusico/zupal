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

class Xtract_Model_UrlImages
extends Xtractlib_Domain_Abstract
{

    public function tableClass()
    {
        return 'Xtract_Model_DbTable_UrlImages';
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
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

    public function __toString() {
        return sprintf(self::IMAGE_TAG, $this->absoulute_url());
    }

   /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ absolute_url @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function absolute_url () {

    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ url @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_url = NULL;
    function url($pReload = FALSE) {
        if ($pReload || is_null($this->_url)):
        // process
            $this->_url = Xtract_Model_Urls::get_url($this->in_url);
        endif;
        return $this->_url;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ href_url @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_href_url = NULL;
    function get_href_url($pReload = FALSE) {
        if ($pReload || is_null($this->_href_url)):
        // process
            $this->_href_url = Xtract_Model_Urls::get_url($this->href_url);
        endif;
        return $this->_href_url;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ html @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_html = NULL;
    function html($pReload = FALSE) {
        if ($pReload || is_null($this->_html)):
        // process
            $this->_html = Xtract_Model_UrlHtmls::get_html($this->in_html);
        endif;
        return $this->_html;
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ link @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param id | string | Xtract_Model_Urls $pURL
 * @param string $pHref
 * @return Xtract_Model_Images
 */
    public static function make ($pURL, $pHref) {
        $url = Xtract_Model_Urls::get_url($pURL);
        $params = array(
            'href' => $pHref,
            'in_url' => $url->identity()
        );

        if (!($img = self::getInstance()->findOne($params))):
            $img = self::getInstance()->get(NULL, $params);
            $img->save();
        endif;
        return $img;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function save () {
        if (!$this->href_url):
            if (preg_match('~^/~', $this->href)):
                $domain = $this->url()->get_domain();
                $abs_string = 'http://' . $domain->host . $this->href;
                Xtractlib_Log::message(__METHOD__ . ': abs_string = '.  $abs_string);

                $href_url = Xtract_Model_Urls::get_url(
                    $abs_string
                    );
                $href_url->save();
                
                $this->href_url = $href_url->identity();
            elseif(preg_match('~^http~', $this->href)):
                $href_url = Xtract_Model_Urls::get_url($this->href);
                $href_url->save();
                $this->href_url = $href_url->identity();
            endif;
        endif;

        parent::save();
    }
}