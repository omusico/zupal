<?

class Pages_View_Helper_Filepage
extends Zend_View_Helper_Abstract {
    public $view;

    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }
    public function filepage($pKey, $page_path, $pTitle = NULL) {
        $pParams = array('name' => 'tag',  'value' => $pKey);

        if ($ion = Model_Zupalions::getInstance()->findOne($pParams)):
            $page = Pages_Model_Zupalpages::getInstance()->for_atom_id($ion->get_atomic_id());
        else:
            $page = new Pages_Model_Zupalpages();

            $pc = file_get_contents($page_path);
            if (preg_match('~<title>(.*)</title>~', $pc, $m)):
                $pTitle = $m[1];
            endif;
            if (preg_match('~<body>(.*)</body>~', $pc, $m)):
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