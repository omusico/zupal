<?

class Zupal_ACL
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_Instance = NULL;
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
     * @var Zend_ACL
     */
    private static $_acl = NULL;
    public static function acl($pReload = FALSE) {
        if ($pReload || is_null(self::$_acl)):
            self::$_acl = new Zend_Acl();
            foreach(Model_Roles::getInstance()->findAll() as $role):
                self::$_acl->addRole(new Zend_Acl_Role($role->identity()));
            endforeach;
        endif;

        return self::$_acl;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ is_allowed @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pResource
     * @return <type>
     */
    public function is_allowed ($pResource, Model_Users $pUser = NULL) {
        if (!$pUser):
            $pUser = Model_Users::current_user();
        endif;

        return self::acl()->isAllowed($pUser ? $pUser->role : Model_Roles::ROLE_ANONYMOUS, $pResource);
    }

}