<?

class Pages_AdminController
extends Zupal_Controller_Abstract
{

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
    public function pagestoreAction () {
        $pt = Pages_Model_Zupalpages::getInstance();
        $pages = $pt->findAll('id');

        $data = array();

        foreach($pages as $page):
            $row = $page->toArray();
            if ($atom = $row->atom()):
                $row = array_merge($atom->toArray('content_'));
            endif;
        endforeach;

        
    }
}