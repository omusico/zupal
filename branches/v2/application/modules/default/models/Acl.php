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
        if ($pLoadFields && is_array($pLoadFields)):
            $out->set_fields($pLoadFields);
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
    public static function find_acl ($pResource, $pRole, $pString = TRUE) {
        if ($pResource instanceof Model_Resources):
            $pResource = $pResource->identity();
        endif;

        if ($pRole instanceof Model_Roles):
            $pRole = $pRole->identity();
        endif;
        error_log(__METHOD__ . ': finding role = ' . $pRole . ', res = ' . $pResource);
        
        $acl = self::getInstance()->findOne(array('resource' => $pResource, 'role' => $pRole));

        if ($pString):
            if (!$acl):
                return '-';
            else:
                return $acl->allow;
            endif;
        elseif($acl):
            return $acl;
        else:
            return FALSE;
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ acl @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * This is the Zend_Acl gateway class. 
     * 
     */
    private static $_acl = NULL;

    /**
     *
     * @param boolean $pReload
     * @return Zend_Acl
     */
    public static function acl($pReload = FALSE) {
        if ($pReload || is_null(self::$_acl)):
            $acl = new Zend_Acl();
            foreach(Model_Resources::getInstance()->find_all() as $res):
                $acl->add($res);
            endforeach;

            foreach(Model_Roles::getInstance()->find_all() as $role):
                $acl->addRole($role);
            endforeach;

            foreach(self::getInstance()->find_all() as $grant):
                if ($grant->allow):
                    $acl->allow( $grant->role, $grant->resource);
                else:
                    $acl->deny( $grant->role, $grant->resource);
                endif;
            endforeach;

            self::$_acl = $acl;
        endif;

        return self::$_acl;
    }

}

