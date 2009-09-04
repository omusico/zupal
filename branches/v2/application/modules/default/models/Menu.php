<?

class Model_Menu extends Zupal_Domain_Abstract {

/**
 * @see Zupal_Formset_Domain::get()
 *
 * @param unknown_type $pID
 * @return Zupal_Domain_Abstract
 */
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
     * @param boolean $pReload
     * @return Zupal_Domain_Abstract
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
        $index = 0;
        foreach($pConfig as $name => $page):
            $menu = $this->add_menu($page, $name, 0, $pModule_creator);
            $menu->save();
        endforeach;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ add_module_menu @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pPage
     * @return <type>
     */
    public function add_menu ($pPage, $pName, $pParent = 0, $pModule_creator = '') {
        $menu = $this->get(NULL, array('parent' => $pParent,
                'name' => $pName,
                'sort_by' => 10,
                'created_by_module' => $pModule_creator));
        $subs = FALSE;
        foreach($pPage as $field => $value):

            switch($field):
                case 'label':
                case 'href':
                case 'resource':
                case 'module':
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ pages @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type>
     * @return <type>
     */
    public function pages () {
        $out = array();

        if($this->href):

            $config = array(
                'label' => $this->label,
                'href' => $this->href);

            $page = new Zend_Navigation_Page_Uri($config);

        else:

            $config = array('label'=> $this->label,
                'module' => $this->module,
                'controller' => $this->controller,
                'action' => $this->action
            );

            $page = new Zend_Navigation_Page_Mvc($config);

        endif;

        $children = array();

        foreach($this->children() as $menu):
            $children = array_merge($children, $menu->pages());
        endforeach;
        $page->setPages($children);
        return array($page);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ children @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type>
     * @return <type>
     */
    public function children () {
        if ($this->name):
            $options = array(
                'module' => $this->module,
                'parent' => $this->identity()
            );
            $children = $this->find($options, 'sort_by');
        else:
            return array();
        endif;

        $req = Zend_Controller_front::getInstance()->getRequest();
        $active_module = $req->getModuleName();
        $active_controller = $req->getControllerName();

        foreach($children as $i => $menu):
            if (($menu->if_module) && ($menu->module != $active_module)):
                unset($children[$i]);
            endif;
        endforeach;

        return $children;
    }

}