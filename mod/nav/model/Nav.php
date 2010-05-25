<?php
/**
 * Description of Nav
 *
 * @author bingomanatee
 */
class Nav_Model_Nav
extends Zupal_Model_Domain_Abstract {

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

    public function handle_event(Zupal_Event_Item $pEvent) {
        switch ($pEvent->name) {
            case 'page_load':
                $this->_handle_page_load($pEvent);
                break;
        }
    }

    public function _handle_page_load(Zupal_Event_Item $pEvent) {

        /* @var $nav_item Nav_Model_Nav */
        $nav_item = $pEvent->target;
        if (!$nav_item || (!($nav_item instanceof Nav_Model_Nav))) {
            $pEvent->status = Zupal_Event_Item::STATUS_ERROR;
            $pEvent->result = 'missing nav item in target';
            return;
        }

        $page = Zupal_View_Page::instance();
        $page->getView()->placeholder('nav')->set($this->render_menu('main'));
        $page->getView()->placeholder('title')->set($nav_item->title());

        if ($nav_item->breadcrumb) {
            $page->getView()->placeholder('breadcrumb')->set($nav_item->breadcrumb);
        }

        $pEvent->responses++;

        if ($nav_item->event) {
            $event = Zupal_Event_Manager::instance()->handle($nav_item->event, $nav_item, $pEvent->params);
            
            switch($event->status) {

                case Zupal_Event_Item::STATUS_DONE:
                    $page->getView()->content = $event->result;
                    break;

                case Zupal_Event_Item::STATUS_WORKING:
                    if ($event->responses > 0) {
                        $page->getView()->content = $event->result;
                    }
                    break;

                case Zupal_Event_Item::STATUS_ERROR:
                    throw new Exception($event->result);
            }
        }
    }

}

