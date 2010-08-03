<?php


/**
 * Description of Loader
 *
 * @author bingomanatee
 */
class Zupal_Module_Loader {

    /* @@@@@@@@@@@@@@@@@@@@@ MOD LOAD @@@@@@@@@@@@@@@@@@ */

    public function load_deps(Zupal_Module_Model_Mods $mod, $pForce = FALSE) {
        if (array_key_exists('deps', $mod->profile)) {
            foreach($mod->profile['deps'] as $dep_mod) {
                $this->mod_load($dep_mod, $pForce);
            }
        }
    }

    function mod_load($mod_name, $pForce = FALSE) {
        $ms = Zupal_Module_Model_Mods::instance();
        if ((!$pForce) && $ms->loaded($mod_name)) {
            return;
        }
        $mod = $ms->mod($mod_name, $pForce);

        $this->load_deps($mod, $pForce);

        if (array_key_exists('handlers', $mod->profile)) {
            $this->_load_handlers($mod);
        }

        if (array_key_exists('resources', $mod->profile)) {
            $this->_load_resources($mod);
        }
        /**
         * do any other custom actions required at boot by module
         */

        $boot_path = $mod->path . D . 'bootstrap.php';
        if (file_exists($boot_path)) {
            require $boot_path;
        }

        $args = array(
                'subject' => $mod->handler(),
                'subject_type' => 'Module_Handler_Module',
                'module' => $mod
        );

        if (!$mod->init) {
            Zupal_Event_Manager::event('add', $args);

            $mod->init = 1;
            $mod->save();
        } elseif ($pForce) {
            Zupal_Event_Manager::event('reset', $args);
            Zupal_Event_Manager::event('add', $args);
        }
        
    }

    private function _load_resources(Zupal_Module_Model_Mods $mod) {

        /**
         * define class paths
         */

        if (array_key_exists('resources', $mod->profile)) {

            foreach($mod->profile['resources'] as $data) {
                $config = array(
                        'basePath'  => $mod->path,
                        'namespace' => $data['namespace'],
                );

                $resourceLoader = new Zend_Loader_Autoloader_Resource($config);
                foreach($data['types'] as $type) {
                    $resourceLoader->addResourceType(
                            $type['name'],
                            $type['dir'],
                            $type['class']
                    );
                }
            }
        }
    }

    private function _load_handlers(Zupal_Module_Model_Mods $mod) {

        if (array_key_exists('handlers', $mod->profile)) {
            foreach($mod->profile['handlers'] as $handler) {
                $handler['module'] = $mod->name;
                Zupal_Event_Manager::add_handler($handler);
            }
        }
    }

    /* @@@@@@@@@@@@@@@@@ INSTANCE @@@@@@@@@@@@@@@@@@@@@@ */

    private static $_instance;

    /**
     * @return Zupal_Module_Loader
     */
    public static function instance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

}

