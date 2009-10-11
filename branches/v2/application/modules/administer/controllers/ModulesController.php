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
        $this->view->modules = $m->findAll('folder');
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
            'controller' => $this->_getParam('controller'),
            'action' => $this->_getParam('action'),
            'module' => $this->_getParam('module'),
            'name' => $this->_getParam('name'),
            'label' => $this->_getParam('label'),
            'resource' => (int) $this->_getParam('resource'),
            'href' => $this->_getParam('href'),
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

}
