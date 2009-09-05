<?php
class Administer_ModulesController extends Zupal_Controller_Abstract
{
    /**
     * @return string
     * 
     * 
     */
    public function init()
    {
        $this->_helper->layout->setLayout('admin');
        
                
        
                        parent::init();
    }
    /**
     * 
     */
    public function indexAction()
    {
        $m = Administer_Model_Modules::getInstance();
        
                
        
                        $m->update_from_filesystem();
        
                
        
                        $this->view->modules = $m->findAll('folder');
    }
    public function activateAction()
    {
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
    public function menueditAction()
    {
    }
    public function menustoreAction()
    {
    }

}
