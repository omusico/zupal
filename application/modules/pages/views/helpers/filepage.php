<?

class Pages_View_Helper_Filepage
extends Zend_View_Helper_Abstract {
    public $view;

    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }
    
    /**
     * returns a rendered page identified by either a tag ($pKey) or an HTML file ($page_path).
     * If the module is passed, path is relative to the modules' pages folder.
     *
     * @param string $pKey
     * @param string $page_path
     * @param string | NULL $pTitle
     * @param string | NULL $pModule
     * @return string (the content of the page view
     */
    public function filepage($pKey, $page_path, $pTitle = NULL, $pModule = NULL) {  
        
        $pParams = array('name' => 'tag',  'value' => $pKey);
        $ion = Model_Zupalions::getInstance()->findOne($pParams);
        
        if ($ion):
            $page = Pages_Model_Zupalpages::getInstance()->for_atom_id($ion->get_atomic_id());
        else:
            $page = new Pages_Model_Zupalpages();
            
            if ($pModule):
                $m = Administer_Model_Modules::getInstance()->get($pModule);
                $pages = $m->module_path('pages');
                $page_path = rtrim($pages, '/') . '/' . ltrim($page_path, '/');
            endif;

            $pc = file_get_contents($page_path);
            if (preg_match('~<title>(.*)</title>~', $pc, $m)):
                $pTitle = $m[1];
            endif;
            if (preg_match('~<body>(.*)</body>~', str_replace("\n", ' ', $pc), $m)):
                $pc = $m[1];
            endif;
            $page->set_content($pc);
            $page->set_title($pTitle);
            $page->save();

            $page->add_ion($pParams);
            $page->add_ion('content_file', $page_path);
        endif;

        $params = array('id' => $page->identity());
        return $this->view->action('view', 'index', 'pages', $params);
    }
}