<?

class Pages_AdminController
extends Zupal_Controller_Abstract {

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
                $row = array_merge($row, Zupal_Util_Array::mod_keys($atom, 'a_'));
            endif;
            $data[] = $row;
        endforeach;

        $this->_store('id', $data, 'a_title');

    }
}