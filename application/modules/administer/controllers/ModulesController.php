<?php
class Administer_ModulesController extends Zupal_Controller_Abstract {
    
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @return <type>
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

    public function menustoreAction() {
        $pages = array();

        $modules = Administer_Model_Modules::getInstance()
            ->find_all('sort_by');

        $mm = Model_Menu::getInstance();

        $module_names = array();

        foreach($modules as $module):
            $module_names[] = $module->identity();
        endforeach;

        $sql = sprintf('(module in ("%s")) AND (parent = 0)', join('","', $module_names));
        // at this point have selected all the menus of all active modules

        // return a tree of pages from each top level page sorted by sort_by and label

        foreach($mm->find_from_sql(array($sql, array('sort_by','label'))) as $menu):
            $new_pages = $menu->pages_tree();
            $pages[] = $new_pages;
        endforeach;

        $this->view->data = new Zend_Dojo_Data('id', $pages, 'label');

        $this->_helper->layout->disableLayout();

    }
}
