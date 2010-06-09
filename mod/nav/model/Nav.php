<?php
/**
 * Description of Nav
 *
 * @author bingomanatee
 */
class Nav_Model_Nav
extends Zupal_Model_Domain_Abstract
implements Zupal_Event_HandlerIF {

    private static $_container;
    protected function container() {
        if (!self::$_container) {
            $schema = $this->schema();
            self::$_container = new Zupal_Model_Container_Mongo('zupal', 'nav', array('schema' => $schema));
        }
        return self::$_container;
    }

    public function title() {
        if ( $this->title) {
            return $this->title;
        } else {
            return $this->label;
        }
    }

    /* @@@@@@@@@@@@@@@@@ MENU @@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     * @param string $pMenu
     * @param string $pParent
     * @return Zend_Navigation_Page_uri[]
     */
    public function menu($pMenu, $pParent = 'root') {
        $crit   = array('parent' => $pParent, 'menu' => $pMenu);
        $menu   = $this->find($crit, NULL, array('weight', 'title'));
        $out    = array();

        foreach($menu as $menu_data) {
            $options = $menu_data->toArray();
            $options['type'] = 'uri';
            unset($options['parent']);
            $out[] = new Zend_Navigation_Page_Uri($options);
        }

        return $out;
    }

    /* @@@@@@@@@@@@@@@@@ INSTANCE @@@@@@@@@@@@@@@@@@@@@@ */

    private static $_instance;

    /**
     * @return Nav_Model_Nav
     */
    public static function instance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function new_data($pData) {
        return new self($pData);
    }

    private $_schema;
    public function schema() {
        if (!$this->_schema) {
            $mod_dom = Zupal_Module_Model_Mods::instance();
            $path = $mod_dom->file('nav','model', 'nav_schema.json');
            $this->_schema = Zupal_Model_Schema_Item::make_from_json($path);
        }
        return $this->_schema;
    }

    function render_menu($pMenu, $pParent = 'root') {
        ob_start();
        ?>
<ul class="menu">
            <? foreach($this->menu($pMenu, $pParent) as $item):
                $parent_crit = array('menu' => $pMenu, 'parent' => $item->name);
                ?>
    <li>
        <a href="<?= $item->uri ?>"><?= $item->label ?></a>
                    <? if ($this->container()->has($parent_crit)): ?>
                        <?= $this->render_menu($pMenu, $item->name) ?>
                    <? endif; ?>
    </li>
            <? endforeach; ?>
</ul>
        <?php
        return ob_get_clean();
    }

    public function path_to_nav($path) {

        $crit = array('uri' => $path);
        $nav_items = $this->find($crit);
        $args = array();
        if (!$nav_items) {
            $parts = split(D, $path);
            $args = array();

            while(!$nav_items && count($parts)) {
                $args[] = array_pop($parts);
                $test = D . join(D, $parts);
                $nav_items = $this->find($crit);
            }
        }
        if ($nav_items && count($nav_items)) {
            $nav_item = array_pop($nav_items);
            return array('nav' => $nav_item, 'args' => $args);
        } else {
            return FALSE;
        }

    }

    public function respond(Zupal_Event_EventIF $pEvent) {
        switch ($pEvent->get_action()) {
            case 'page':
           //     $this->_handle_page($pEvent);
                break;
        }
    }

    public function _handle_page(Zupal_Event_Item $pEvent) {

        switch ($pPhase) {
            case Zupal_Event_HandlerIF::PHASE_PRE:

                $pEvent->args()->offsetSet('main_menu', $this->render_menu('main'));
                $pEvent->args()->offsetSet('title', $nav_item->title());

                if ($nav_item->breadcrumb) {
                    $pEvent->args()->offsetSet('breadcrumb', $nav_item->breadcrumb);
                }

                if ($nav_item->content_handler) {

                    $class = $nav_item->content_handler;

                    $ch = new $class();
                    
                    $em = Zupal_Event_Manager::instance();

                    $params = array(
                            'subject' => $ch,
                            'nav' => $nav_item
                    );

                    $e = $em->handle('render', $params);

                    if ($e->get_status() == Zupal_Event_EventIF::STATUS_ERROR) {
                        $pEvent->set_result("Error rendering content with $class: " . $e->get_result());
                        $pEvent->set_status(Zupal_Event_EventIF::STATUS_ERROR);
                    } else {
                        $pEvent->args()->offsetSet('content', $e->get_result());
                    }
                } else {
                    $pEvent->set_status(Zupal_Event_EventIF::STATUS_ERROR);
                    $pEvent->set_response('no nav event');
                }

                break;

        }
    }
}

