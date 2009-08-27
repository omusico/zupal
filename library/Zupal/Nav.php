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
    public function pages ($pModule) {
        $pages = array();
        $req = Zend_Controller_front::getInstance()->getRequest();
        $active_module = $req->getModuleName();
        $active_controller = $req->getControllerName();

        $sql = array('(required = 1) OR (active = 1)', 'sort_by');
        $modules = Administer_Model_Modules::getInstance()->find_from_sql($sql, TRUE, FALSE);
        foreach($modules as $module):
            $module->menu();
            $module_names[] = '"' . $module->folder . '"';
        endforeach;


        $module_is_active = sprintf(' (module = "%s") ', $active_module);

        $mm = Model_Menu::getInstance();

        $sql = sprintf('(module in (%s)) AND (parent = 0)', join(',', $module_names));
        //// at this point have selected all the menus of this module

        $sql .= sprintf(' AND ((if_module = 0) OR (%s)) ', $module_is_active);
        // if the menu only viewable in the context of the current menu,
        // require the module to be current

        error_log(__METHOD__ . ': menu sql = ' . $sql);

        foreach($mm->find_from_sql(array($sql, array('sort_by','label'))) as $menu):
            $new_pages = $menu->pages();
            $pages = array_merge($pages,  $new_pages);
        endforeach;
       return new Zend_Navigation($pages);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ filter @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pPages,
     * @param string $pModule
     * @param string $pController
     * @return <type>
     */
    public function filter ($pPages, $pModule, $pController) {
        $out = array();
        foreach($pPages as $key => $page):
            if ($page instanceof Zend_Navigation_Page_Mvc):
                $active = !(strcasecmp($page->getModule(), $pModule) || strcasecmp($page->getController(), $pController));
                if ($page->ifactive && (!$active)):
                    continue;
                endif;
            endif;
            $subpages = $page->getPages();
            if (count($subpages)):
                $page->setPages($this->filter($subpages, $pModule, $pController));
            endif;
            $out[] = $page;
        endforeach;
        
        return $out;
    }

}