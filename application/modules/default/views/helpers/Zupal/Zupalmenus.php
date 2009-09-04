<?

class Zupal_Helper_Zupalmenus extends Zend_View_Helper_Abstract {

    public function getView()
    {
        return $this->view;
    }

    public function zupalmenus() {
        return $this->getView()->navigation()->menu()->renderMenu($this->pages());
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ user @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_user = NULL;
    function get_user($pReload = FALSE) {
        if ($pReload || is_null($this->_user)):
        // process
            $this->_user = Model_Users::current_user();
        endif;
        return $this->_user;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ pages @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return Zend_Navigation_Pages[]
     */
    public function pages () {
        $pages = array();
        $req = Zend_Controller_front::getInstance()->getRequest();
        $active_module = $req->getModuleName();
        $active_controller = $req->getControllerName();

        // retrieve a list of the active modules

        $sql = array('(required = 1) OR (active = 1)', 'sort_by');
        $modules = Administer_Model_Modules::getInstance()
            ->find_from_sql($sql, TRUE, FALSE);
            
        foreach($modules as $module):
            if (1 || !$module->menu_loaded) :
                $module->load_menus();
            endif;
            
            $module_names[] = '"' . $module->folder . '"';
        endforeach;

        $mm = Model_Menu::getInstance();

        $sql = sprintf('(module in (%s)) AND (parent = 0)', join(',', $module_names));
        //// at this point have selected all the menus of all active modules

        $module_is_active = sprintf(' (module = "%s") ', $active_module);

        $sql .= sprintf(' AND ((if_module = 0) OR (%s)) ', $module_is_active);
        // if the menu only viewable in the context of the current menu,
        // require the module to be current

        // return a tree of pages from each top level page sorted by sort_by and label

        foreach($mm->find_from_sql(array($sql, array('sort_by','label'))) as $menu):
            $new_pages = $menu->pages();
            $pages = array_merge($pages,  $new_pages);
        endforeach;

        return new Zend_Navigation($pages);
    }

}