<?php

class Model_Resources extends Zupal_Domain_Abstract
implements Zend_Acl_Resource_Interface
{

    public function tableClass()
    {
        return 'Model_DbTable_Resources';
    }

    public function get ($pID = null, $pLoadFields = NULL) {
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
     * @return Model_Resources
     */
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ ResourceId @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return $this->identity();
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ add_resources @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pResources
     * @return void
     */
    public static function add_resources ($pResources, $pModule, $pLoaded) {
        foreach($pResources as $name => $res):
            $key = $pModule . '_' . $name;
            if ($old_resource = self::getInstance()->get($key)):
                if ($old_resource->is_saved()):
                    continue;
                endif;
            endif;
            $resource = new self();

            $resource->resource_id = $key;
            $resource->title = $res['title'];
            $resource->module = $pModule;
            $resource->rank = (isset($res['rank'])) ? $res['rank'] : 1;
            $resource->save();
        endforeach;
    }
}


