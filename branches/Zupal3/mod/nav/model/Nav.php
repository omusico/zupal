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

}

