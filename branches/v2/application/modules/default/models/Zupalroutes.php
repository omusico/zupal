<?php

class Model_Zupalroutes extends Zupal_Domain_Abstract
{

    private static $_instance = 'zupal_routes';

    public function tableClass()
    {
        return 'Model_DbTable_Zupalroutes';
    }

    public function get($pID = 'NULL', $pLoadFields = 'NULL')
    {
        $out = new self($pID);
            if ($pLoadFields && is_array($pLoadFields)):
                $out->set_fields($pLoadFields);
            endif;
            return $out;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_Instance = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Model_Zupalroutes
     */
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ add_routes @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pAdd_Routes
     * @return void
     */
    public static function add_routes ($pAdd_routes, $pFrom_Module) {
        foreach($pAdd_routes as $route => $data):
            if (self::getInstance()->get($route)):
                continue;
             endif;

             $route = new self();
             $route->route_id = $route;
             foreach($route['defaults'] as $key => $value):
                $route->$key = $value;
             endforeach;
             $route->created_by_module = $pFrom_Module;
             $route->save();
        endforeach;
    }
}

