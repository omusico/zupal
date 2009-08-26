<?

class Zupal_Nav
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ user @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_user = NULL;
    function get_user($pReload = FALSE) {
        if ($pReload || is_null($this->_user)):
        // process
            $this->_user = Model_Users::current_user();
        endif;
        return $this->_user;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_Instance = NULL;

    /**
     *
     * @param boolean $pReload
     * @return Zupal_Nav
     */
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ pages @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function core_pages () {
        $config = new Zend_Config_Ini(dirname(__FILE__) . '/core_pages.ini', 'pages');
        return new Zend_Navigation($config);
        $out = array();
        foreach($config as $page):
            $out[] = Zend_Navigation_Page::factory($page);
        endforeach;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ core_page_options @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function core_page_options () {
        return array();
    }
}