<?php

function mod_init() {
    global $mod_paths, $mod_status, $event_manager, $event_manager_container;

    $event_manager_container = new Zupal_Model_Container_Mongo('zupal', 'event');
    $event_manager  = new Zupal_Event_Manager($event_manager_container);
    
    $mod_paths      = array();
    $mod_status     = array();

    $di = new DirectoryIterator(dirname(__FILE__));

    foreach($di as $d) {
        if ((!is_dot()) && is_dir($d)) {
            $path = $d->getPathname() . D . 'profile.json';
            $mod = basename($d->getPathname());
            if (file_exists($path)) {
                $mod_paths[$mod] = $d->getPathname();
            }
        }
    }

    foreach($mod_paths as $mod => $path) {
        mod_load($mod, $path);
    }
}

function mod_load($mod, $path = NULL) {
    global $mod_status, $mod_paths;

    if(!$path) {
        $path = $mod_paths[$path];
    }

    if(array_key_exists($mod, $mod_status)) {
        return;
    }

    $mod_json_path = $mod_paths[$mod] . D . 'profile.json';
    $mod_info = Zend_Json::decode(file_get_contents($mod_json_path));

    if (array_key_exists('deps', $mod_info)) {
        foreach($mod_info['deps'] as $dep_mod) {
            mod_load($dep_mod);
        }
    }

    require $path . D . 'bootstrap.php';
}

mod_init();