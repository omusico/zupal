<?
class Pages_AdminController
extends Zupal_Controller_Abstract {
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ preDispatch @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 *
 */

    public function preDispatch () {
        parent::postDispatch();
        $this->_helper->layout->setLayout('admin');
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     */

    public function indexAction () {
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ pagestoreAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     */

    public function pagesstoreAction () {
        $pt = Pages_Model_Zupalpages::getInstance();
        $pages = $pt->findAll('id');
        $data = array();
        foreach($pages as $page):
            $row = $page->toArray();
            if ($atom = $page->atom(TRUE)):
                if ($publish_status = $page->get_publish_status()):
                    $psa = Zupal_Util_Array::mod_keys($publish_status->toArray(), 'ps_');
                else:
                    $psa = array();
                endif;
                $row = array_merge($row, Zupal_Util_Array::mod_keys($atom, 'a_'), $psa);
            endif;
            $data[] = $row;
        endforeach;
        $this->_store('id', $data, 'a_title');
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ deleteAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     */

    public function deleteAction () {
        $this->view->id = $this->_getParam('id');
    }

    public function editAction()
    {
        $id = $this->_getParam("id",  NULL );
        $this->view->id = $id;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ editexecuteAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function editexecuteAction () {
        $form = new Pages_Form_Zupalpages($this->_getParam('id'));
        if ($form->isValid($this->_getAllParams())):
            $form->save();
        else:
            $params = $this->_getAllParams();
            $params['reload'] = TRUE;
            $params['error'] = 'cannot save page';
            return $this->_forward('edit', NULL, NULL, $params);
        endif;
        $this->_forward('view', 'index', NULL, $this->_getParam('id'));
    }
    
}

