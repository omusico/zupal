<?php


/**
 * Description of Loader
 *
 * @author bingomanatee
 */
class Zupal_Module_Loader {

    /* @@@@@@@@@@@@@@@@@@@@@ MOD LOAD @@@@@@@@@@@@@@@@@@ */

    public function load_deps(Zupal_Module_Model_Mods $mod) {
        if (array_key_exists('deps', $mod->profile)) {
            foreach($mod->profile['deps'] as $dep_mod) {
                $this->mod_load($dep_mod);
            }
        }
    }

    function mod_load($mod_name) {
        $ms = Zupal_Module_Model_Mods::instance();
        $mod = $ms->mod($mod_name);
        $this->load_deps($mod);

        $em = Zupal_Event_Manager::instance();

        /**
         * Load Handlers
         */

        if (array_key_exists('handlers', $mod->profile)) {
            foreach($mod->profile['handlers'] as $action => $s_handlers) {
                foreach ($s_handlers as $subject => $handlers) {
                    foreach($handlers as $handler) {
                        $em->add_handler($action, $handler, $subject);
                    }
                }
            }
        }

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

        /**
         * do any other custom actions required at boot by module
         */

        $boot_path = $mod->path . D . 'bootstrap.php';
        if (file_exists($boot_path)) {
            require $boot_path;
        }
    }


}
