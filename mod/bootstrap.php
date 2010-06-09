<?php

global $event_manager, $event_manager_container;

//   $mod_paths = Zupal_Module_Path::instance();

$event_manager_container = new Zupal_Model_Container_Mongo('zupal', 'event');
$event_manager  = new Zupal_Event_Manager($event_manager_container);

$loader = new Zupal_Module_Loader();

$di = new DirectoryIterator(dirname(__FILE__));
foreach($di as $d) {
    if ($d->isDot()  || (! $d->isDir())) {
        continue;
    }
    $mod_path = $d->getPathname();

    $path = $mod_path . D . 'profile.json';
    if (!file_exists($path)) {
        continue;
    }

    $mod_name = basename($mod_path);

    $loader->mod_load($mod_name, $mod_path);
}
