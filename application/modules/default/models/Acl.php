<?php

class Model_Acl extends Zupal_Domain_Abstract
{

    private static $_instance = 'zupal_acl';

    public function tableClass()
    {
        return 'Model_DbTable_Acl';
    }

    public function get($pID = NULL, $pLoadFields = NULL)
    {
        $out = new self($pID);
        if ($pLoad_Fields && is_array($pLoad_Fields)):
            $out->set_fields($pLoad_Fields);
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ find_acl @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pResource
     * @param $pRole
     * @return Model_ACL | NULL
     */
    public function find_acl ($pResource, $pRole, $pString = TRUE) {
        if ($pResource instanceof Model_Resources):
            $pResource = $pResource->identity();
        endif;

        if ($pRole instanceof Model_Roles):
            $pRole = $pRole->identity();
        endif;

        $acl = $this->findOne(array('resource' => $pResource, 'role' => $pRole));

        if ($pString):
            if (!$acl):
                return '-';
            else:
                return $acl->allow;
            endif;
        else:
            return $acl;
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_acl @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pResource
     * @param string $pRole
     * @param string $pAllow
     * @return string
     */
    public function set_acl ($pResource, $pRole, $pAllow) {
        if ($pResource instanceof Model_Resources):
            $pResource = $pResource->identity();
        endif;

        if ($pRole instanceof Model_Roles):
            $pRole = $pRole->identity();
        endif;

        $acl = $this->find_acl($pResource, $pRole, FALSE);

        if (!$acl):
            $acl = $this->get();
            $acl->resource = $pResource;
            $acl->role = $pRole;
        endif;

       $acl->allow = $pAllow;

       $acl->save();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_Instance = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Model_Acl
     */
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }


}

