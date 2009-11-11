<?

class Model_Menu extends Zupal_Domain_Abstract {

/**
 * @see Zupal_Formset_Domain::get()
 *
 * @param unknown_type $pID
 * @return Zupal_Domain_Abstract
 */
    public function get ($pID = null, $pLoadFields = NULL) {
        $out = new self($pID);
        if ($pLoadFields && is_array($pLoadFields)):
            $out->set_fields($pLoadFields);
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_Instance = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Model_Menu
     */
    static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self(Zupal_Domain_Abstract::STUB);
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * @see Zupal_Formset_Domain::get_table_class()
     *
     */
    public function tableClass () {
        return self::TABLE_CLASS;
    }
    const TABLE_CLASS = 'Model_DbTable_Menu';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ add_module_menus @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pParam
     * @return <type>
     */
    public function add_menus (array $pConfig, $pModule_creator) {
        
        $new_menus = array();

        foreach($pConfig as $name => $page):
            $menu = $this->add_menu($page, $name, 0, $pModule_creator);
            $new_menus[] = $menu;
        endforeach;

        return $new_menus;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ add_module_menu @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pPage
     * @return <type>
     */
    public function add_menu ($pPage, $pName, $pParent = 0, $pModule_creator = '') {
        $menu = $this->get(NULL, array(
                'parent' => $pParent,
                'name' => $pName,
                'sort_by' => 10,
                'panel' => 'main',
                'created_by_module' => $pModule_creator));

        if ($pName == 'home'):
           error_log('home');
        endif;

        $subs = FALSE;
        foreach($pPage as $field => $value):

            switch($field):
                case 'label':
                case 'href':
                case 'resource':
                case 'module':
                case 'panel':
                case 'controller':
                case 'action':
                case 'if_controller':
                case 'if_module':
                case 'callback_class':
                case 'sort_by':
                    $menu->$field = $value;
                    break;

                case 'pages':
                    $subs = $value;
                    break;
            endswitch;

        endforeach;

        $menu->set_path();
        $menu->save();

        if ($subs):
            $subindex = 0;
            foreach($subs as $name => $page):
                $submenu = $this->add_menu($page, $name, $menu->identity(), $pModule_creator);
                $submenu->sort_by = ++$subindex;
                $submenu->save();
            endforeach;
        endif;

        return $menu;
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ parent @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    private $_parent = NULL;
    function parent($pReload = FALSE) {
        if ($pReload || is_null($this->_parent)):
        // process
            $value = $this->parent? $this->get($this->parent) : FALSE;
            $this->_parent = $value;
        endif;
        return $this->_parent;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_path @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> 
     * @return <type>
     */
    public function set_path () {
        if ($parent = $this->parent()):
            $root = $parent->path;
        else:
            $root = array();
            if ($this->module) $root[] = $this->module;
            if ($this->panel != $nav) $root[] = $this->panel;
            $root = join('-', $root);
        endif;
        $this->path = $root . self::PATH_SEPARATOR .  $this->name;
    }

    const PATH_SEPARATOR = ':';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ module @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_module = NULL;
    function module($pReload = FALSE) {
        if ($pReload || is_null($this->_module)):
            $module = NULL;

            if ($this->module):
                $module = Administer_Model_Modules::getInstance()->get($this->module);
            elseif ($this->created_by_module):
                $module = Administer_Model_Modules::getInstance()->get($this->created_by_module);
            endif;

        // process
            $this->_module = $module;
        endif;
        return $this->_module;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ pages @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 *
 * @param string $pModule
 * @param string $pController
 * @return Zend_Navigation_Page
 */
    public function page ($pModule = NULL, $pController = NULL) {
        $module = $this->module();

        if ($module):
            if (!$module->is_active()):
                return array();
            endif;

            if ($this->if_module && $pModule && ($pModule != $this->module)):
                return array();
            endif;

            if ($this->if_controller && $pModule && ($pModule != $this->module) && $pController && ($pController != $this->controller)):
                return array();
            endif;

        endif;
        
        if($this->href):

            $config = array(
                'label' => $this->label,
                'href' => $this->href
            );

            if ($this->resource):
                $config['resource'] = $this->resource;
            endif;

            $page = new Zend_Navigation_Page_Uri($config);

        else:

            $config = array('label'=> $this->label,
                'module' => $this->module,
                'controller' => $this->controller,
                'action' => $this->action
            );

            if ($this->resource):
                $config['resource'] = $this->resource;
            endif;
            
            $page = new Zend_Navigation_Page_Mvc($config);

        endif;

        $children = array();

        foreach($this->children() as $menu):
            if ($c_page = $menu->page($pModule, $pController)):
                $children[] = $c_page;
            endif;
        endforeach;
        if ($children):
            $page->setPages($children);
        endif;
        return $page;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ pages @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pPanel = 'main'
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

        foreach($this->find(array('panel' => $pPanel, 'parent' => 0), 'sort_by') as $menu):
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ pages_tree @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @returnarray
     */
    public function pages_tree () {
        $data = $this->toArray();

        $data['children'] = array();

        foreach($this->children() as $menu):
            $data['children'][] = $menu->pages_tree();
        endforeach;

        return $data;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ children @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    /**
     * @return Model_Menu[]
     */
    public function children () {
        $options = array(
            'parent' => $this->identity()
        );
        $children = $this->find($options, 'sort_by');

        return $children;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ panels @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return String[]
     */
    public function panels () {
        $sql = sprintf('SELECT distinct `panel` FROM `%s` ORDER BY `panel`',
            $this->table()->tableName());
        return $this->table()->getAdapter()->fetchCol($sql);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function save () {
        parent::save();

        if (func_num_args() && func_get_arg(0)):
        // if there is a true first paremeter skip the resort script.
        // should break recursion
            return;
        endif;

        if ($this->parent()):
            $siblings = $this->parent()->children();
        else:
            $params = array('panel' => $this->panel, 'parent' => 0);
            $siblings = $this->find($params, 'sort_by');
        endif;
        
        foreach ($siblings as $new_sort_by => $menu):
            $menu->save(TRUE);
        endforeach;
        
    }
}