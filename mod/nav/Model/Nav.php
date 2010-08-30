<?php
/**
 * Description of Nav
 *
 * @author bingomanatee
 */
class Nav_Model_Nav
extends Zupal_Model_Domain_Abstract {

    /* @@@@@@@@@@@@@@@@@ DOMAIN INTERFACE METHODS @@@@@@@@@@@@@@@@@@@@@ */
    
    private static $_container;
    protected function container() {
        if (!self::$_container) {
            $schema = $this->schema();
            self::$_container = new Zupal_Model_Container_MongoCollection('zupal', 'nav', array('schema' => $schema));
        }
        return self::$_container;
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

    /* @@@@@@@@@@@@@@@@@@@@@@ CLASS SPECIFIC METHODS @@@@@@@@@@@@@@@@@@@ */


    public function title() {
        if ( $this->title) {
            return $this->title;
        } else {
            return $this->label;
        }
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
            $options['pages'] = $this->menu($pMenu, $menu_data->name);
            $out[] = $options;
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

}

