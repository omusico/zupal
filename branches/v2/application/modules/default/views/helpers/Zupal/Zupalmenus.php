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
        return Model_Menu::getInstance()->pages($pPanel);
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