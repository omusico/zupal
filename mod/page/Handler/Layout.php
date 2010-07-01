<?php

/**
 * Description of Layout
 *
 * @author bingomanatee
 */
class Page_Handler_Layout
implements Zupal_Event_HandlerIF {
    public function respond(Zupal_Event_EventIF $pEvent) {
        switch($pEvent->get_action()) {
            case 'layout':
                $page_mod = Zupal_Module_Model_Mods::instance()->mod('page');
                $linst = Page_Model_Layouts::instance();
                $fa = $linst ->find();
                $page_layout = NULL;
                $any_page_layouts = array();
                foreach($fa as $layout){
                    if ($layout->page = '*'){
                        $any_page_layouts[] = $layout;
                    } elseif ($fa->page == $pEvent->args('nav')->name){
                        $page_layout = $fa;
                        break;
                    }
                }

                if ((!$page_layout) && count($any_page_layouts)){
                    $page_layout = array_shift($any_page_layouts);
                }

                if ($page_layout){
                    $pEvent->set_result($page_layout->render($pEvent));
                    $pEvent->set_status(Zupal_Event_EventIF::STATUS_DONE);
                }

                break;
        }
    }
}
