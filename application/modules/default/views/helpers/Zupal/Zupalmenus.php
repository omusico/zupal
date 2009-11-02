<?

class Zupal_Helper_Zupalmenus extends Zend_View_Helper_Abstract {

    public function getView()
    {
        return $this->view;
    }

    public function zupalmenus() {
        $menu = $this->getView()->navigation()->menu();
        $menu->setAcl(Model_Acl::acl());
        $pages = $this->pages();

        if (Model_Users::current_user()):
            $menu->setRole(Model_Users::current_user()->role);
        else:
            $menu->setRole('anonymous');
        endif;
        
        $router = Zend_Controller_Front::getInstance()->getRouter();
        return $menu->renderMenu($pages, array('router' => $router));
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
     * @return Zend_Navigation
     */
    public function pages ($pPanel = 'main') {
        $pages = array();
        
        $req = Zend_Controller_front::getInstance()->getRequest();
        $active_module = $req->getModuleName();
        $active_controller = $req->getControllerName();
            
        $sql = array('(required = 1) OR (active = 1)', 'sort_by');
        $modules = Administer_Model_Modules::getInstance()->find_from_sql($sql, TRUE, FALSE);

        foreach($modules as $module):
            if (!$module->active) continue;

            $module->load_menus();
            $module_names[] = '"' . $module->folder . '"';
        endforeach;

        $mm = Model_Menu::getInstance();
        foreach($mm->find(array('panel' => $pPanel, 'parent' => 0), 'sort_by') as $menu):
            if ($new_page = $menu->page($active_module, $active_controller)):
               $pages[] = $new_page;
            endif;
        endforeach;
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $fake_route = new Zend_Controller_Request_Http();
        $fake_route->setRequestUri('/');
        $router->route($fake_route);
        return new Zend_Navigation($pages);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function save () {
        return parent::save();
    }
}