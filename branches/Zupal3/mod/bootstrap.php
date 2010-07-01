<?php

global $event_manager, $event_manager_container;

//   $mod_paths = Zupal_Module_Path::instance();

$event_manager_container = new Zupal_Model_Container_Mongo('zupal', 'event');
$event_manager  = new Zupal_Event_Manager($event_manager_container);

$loader     = Zupal_Module_Loader::instance();
$mod_stub   = Zupal_Module_Model_Mods::instance();

$di = new DirectoryIterator(dirname(__FILE__));
foreach($di as $d) {
    if ($d->isDot()  || (! $d->isDir())) {
        continue;
    }
    $mod_path = $d->getPathname();

    $mod_name = basename($mod_path);
    if (!file_exists($mod_stub->profile_path($mod_name))){
        continue;
    }

    $loader->mod_load($mod_name);
}
