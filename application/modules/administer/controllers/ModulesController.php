<?php
class Administer_ModulesController extends Zupal_Controller_Abstract {
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 *
 */

    public function preDispatch () {
        $this->_helper->layout->setLayout('admin');
        $u = Model_Users::current_user();
        if (!$u || ! $u->can('site_admin')):
            $param = array('error' => 'This area is reserved for administrators');
            return $this->_forward('insecure', 'error', 'administer', $param);
        endif;
    }

    /**
     *
     */

    public function indexAction() {
        $m = Administer_Model_Modules::getInstance();
        $m->update_from_filesystem();
        $this->view->modules = $modules = $m->findAll('folder');
        foreach($modules as $module):
            if ($module->is_active()  && (! $module->is_loaded())):
                $this->_load($module);
            endif;
        endforeach;
    }

    private function _load(Administer_Model_Modules $m) {
        try {
            $load_data = $m->get_load();
            if ($load_data):
                foreach($load_data as $class => $options):
                    switch($class):
                        case 'resource':
                        case 'resources':
                            Model_Resources::getInstance()->add_resources($options, $m->folder, $m->is_active());
                        break;
                        case 'route':
                        case 'routes':
                            Model_Zupalroutes::getInstance()->add_routes($options, $m->folder, $m->is_active());
                        break;
                        default:
                            throw new Exception("can't load resource $class");
                    endswitch;
                endforeach;
            endif;
            $m->resource_loaded = TRUE;
            $m->save();
        } catch(Exception $e)  {
            error_log(__METHOD__ . ': error loading ' . $m->folder);
            throw $e;
        }

    }

    public function activateAction() {
        $module_name = $this->_getParam('module_name');
        $module = Administer_Model_Modules::getInstance()->get($module_name);
        if (!$module):
            $this->_forward('index', 'error', 'default', array(sprintf(__METHOD__ . ': no module named "%s"', $module_name)));
        endif;
        $on = $this->_getParam('on');
        $module->active = $on ? 1 : 0;
        $module->save();
        $this->_forward('index', NULL, NULL, array('message' => sprintf('active status of module "%s" set to "%s"', $module_name, $on ? 'YES' : 'NO')));
    }

    public function menueditAction() {
    }

/**
 * NOTE: this is a resource for menu editing; therefore ALL menus of ALL modules
 * are listed, and are parented to panels.
 */

    public function menustoreAction() {
        $panel_pages = array();
        $menut = Model_Menu::getInstance();
        $panel_names = $menut->panels();
        $pages = array();
        // at this point have selected all the menus of all active modules
        // return a tree of pages from each top level page sorted by sort_by and label
        foreach($panel_names as $panel):
            $panel_data = array('id' => $panel, 'label' => ucwords(str_replace('_', ' ', $panel)),
                'children' => array());
            foreach($menut->find(array('panel' => $panel, 'parent' => 0), 'sort_by') as $menu):
                $panel_data['children'][] = $menu->pages_tree();
            endforeach;
            $pages[] = $panel_data;
        endforeach;
        $this->view->data = new Zend_Dojo_Data('id', $pages, 'label');
        $this->_helper->layout->disableLayout();
    }

    public function menueditexecuteAction()
    {
        $data = array(
            'if_controller' => $this->_getParam('if_controller'),
            'if_module' => $this->_getParam('if_module'),
            'controller' => $this->_getParam('menucontroller'),
            'action' => $this->_getParam('menuaction'),
            'module' => $this->_getParam('menumodule'),
            'name' => $this->_getParam('name'),
            'label' => $this->_getParam('label'),
            'resource' => (int) $this->_getParam('resource'),
            'href' => $this->_getParam('href'),
            'resource' => $this->_getParam('resource'),
            'parameters' => $this->_getParam('parameters'),
            'parent' => $this->_getParam('parent')
        );
        $id = (int) $this->_getParam('id', NULL);
        $menu = Model_Menu::getInstance()->get($id, $data);
        if (!$menu->panel) $menu->panel = 'main';
        $menu->save();
        $params = array('message' => $data->label . ' updated');
        $this->_forward('menuedit', NULL, NULL, $params);
    }

    public function menuexportAction() {
    }

    public function menuexportexecuteAction() {
        $root_module = $this->_getParam("root_module",  NULL );

        $params = array('parent' => 0);

        if (strcasecmp($root_module, 'all')):
            $params['created_by_module'] = $root_module;
        endif;

        $menus = Model_Menu::getInstance()->find($params, array('panel','sort_by'));

        $out = array();

        foreach($menus as $i => $menu):
            $md = $menu->toArray();
            $data = $this->_menu_ini($menu);
            $data['sort_by'] = $i + 1;
            $out[$menu->name] = $data;
        endforeach;
        $config  = new Zend_Config(array('menu' => $out));
        error_log(__METHOD__ . ': data = ' . print_r($config, 1));
        $ini  = new Zupal_Config_Writer_Ini(array('config' => $config));

        $this->view->data = $ini->__toString();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _menu_ini @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param Model_Menu $pMenu
     * @return array
     */
    public function _menu_ini (Model_Menu $pMenu) {
        $out = $pMenu->toArray();
        unset($out['parent']);
        unset($out['id']);
        unset($out['path']);

        foreach(array('href', 'callback_class', 'parameters') as $param):
            if (!$out[$param]):
                unset($out[$param]);
            endif;
        endforeach;

        foreach($pMenu->children() as $i =>  $child):
            $data = $this->_menu_ini($child);
            $data['sort_by'] = $i + 1;
            $out['pages'][$child->name] = $data;
        endforeach;

        return $out;
    }

/* @@@@@@@@@@@@@ EXTENSION BOILERPLATE @@@@@@@@@@@@@@ */

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controller_dir @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function controller_dir () {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }
    
}

