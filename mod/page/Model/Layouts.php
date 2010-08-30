<?php

/**
 * Holds a set of layout directives - determines which layout a given page requires.
 *
 * @author bingomanatee
 */
class Page_Model_Layouts
extends Zupal_Model_Domain_Abstract {

    public function render(Zupal_Event_EventIF $pEvent) {
        switch(strtolower($this->style)) {
            case 'zend_layout':
                return $this->_render_zend_layout($pEvent);
                break;

            default:
                return $this->stock($pEvent);
        }
    }

    protected function _render_zend_layout(Zupal_Event_EventIF $pEvent) {
        $p = new Page_View_ZendLayout();
        $pEvent->args()->offsetSet('page_layout', $this);
        $t_path = Zupal_Module_Model_Mods::instance()->mod_path('page') . D . $this->file;

        $pEvent->args()->offsetSet('layout_path', dirname($t_path));
        $pEvent->args()->offsetSet('layout', basename($t_path));
        return $p->render($pEvent->args());
    }

    public function stock(Zupal_Event_EventIF $pEvent) {
        ob_start();

        ?>
<html>
    <head>
        <title>Title</title>
    </head>
    <body>
        <table>
            <tr><td class="page_head" colspan="2">header</td></tr>

            <tr><td class="page_nav">nav</td>
                <td class="page_content"><?= $pEvent->args('content') ?></td></tr>
            <tr><td class="page_footer" colspan="2">footer</td></tr>

        </table>
    </body>
</html>
        <?php

        return ob_get_clean();

    }

    /* @@@@@@@@@@@@@@ DOMAIN INTERFACE METHODS @@@@@@@@@@@@@@@@@@@@@ */

    private static $_container;
    protected function container() {
        if (!self::$_container) {
            $schema = $this->schema();
            self::$_container = new Zupal_Model_Container_MongoCollection('zupal', 'layouts', array('schema' => $schema));
        }
        return self::$_container;
    }

    private $_schema;
    public function schema() {
        if (!$this->_schema) {
            $mod_dom = Zupal_Module_Model_Mods::instance();
            $path = $mod_dom->file('page','Model', 'layout_schema.json');
            $this->_schema = Zupal_Model_Schema_Item::make_from_json($path);
        }
        return $this->_schema;
    }

    public function new_data($pData) {
        return new self($pData);
    }

    /* @@@@@@@@@@@@@@@@@ INSTANCE @@@@@@@@@@@@@@@@@@@@@@ */

    private static $_instance;

    /**
     * @return Page_Model_Layouts
     */
    public static function instance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

}

