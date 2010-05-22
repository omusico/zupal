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

            $mod_paths = Zupal_Module_Path::instance();
            $path = $mod_paths->file('nav','model', 'nav_schema.json');
            $schema = Zupal_Model_Schema_Item::make_from_json($path);
            self::$_container = new Zupal_Model_Container_Mongo('zupal', 'nav', array('schema' => $schema));
        }
        return self::$_container;
    }

    /* @@@@@@@@@@@@@@@@@ MENU @@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function menu($pName, $pParent = 'root'){
        $crit   = array('parent' => $pParent, 'menu' => $pName);
        $menu   = $this->find($crit, NULL, array('weight', 'title'));
        $out    = array();

        foreach($menu as $menu_data){
            $options = $menu_data->toArray();
            $options['type'] = 'uri';
            $out[] = new Zend_Navigation_Page_Uri($options);
        }

        return $out;
    }

    /* @@@@@@@@@@@@@@@@@ INSTANCE @@@@@@@@@@@@@@@@@@@@@@ */

    private static $_instance;

    /**
     * @rerurn Nav_Model_Nav
     */
    public static function instance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}

