<?

class Model_Users
extends Zupal_Domain_Abstract {

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 * @see Zupal_Formset_Domain::get_table_class()
 */
    public function tableClass () {
        return self::TABLE_CLASS;
    }
    const TABLE_CLASS = 'Model_DbTable_Users';
    
    /**
     * @see Zupal_Formset_Domain::get()
     *
     * @param unknown_type $pID
     * @return Zupal_Formset_Domain
     *
     */
    public function get ($pID = null, $pLoad_Fields = NULL) {
        $out = new self($pID);
        if ($pLoad_Fields && is_array($pLoad_Fields)):
            $out->set_fields($pLoad_Fields);
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_Instance = NULL;
    /**
     *
     * @return Model_Users
     */
    public static function getInstance() {
        if (is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_fields = NULL;
    public static function fields() {
        if (is_null(self::$_fields)):
        // process
            self::$_fields = array_keys(self::getInstance()->toArray());
        endif;
        return self::$_fields;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ current_user @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_current_user = NULL;
    /**
     *
     * @param int $pReload
     * @return Model_Users
     */
    public static function current_user($pReload = FALSE) {
        if ($pReload || !(self::$_current_user)):
                $auth = Zend_Auth::getInstance();
                if ($auth->hasIdentity()):
                        $identity = $auth->getIdentity();
                        self::$_current_user = self::getInstance()->get($identity->identity());
                else:
                        self::$_current_user = FALSE;
                endif;
                // process
        endif;
        return self::$_current_user;
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ clear_current_user @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public static function clear_current_user () {
        Zend_Auth::getInstance()->clearIdentity();
        self::$_current_user = NULL;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ role @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_role = NULL;
    function get_role($pReload = FALSE) {
        if ($pReload || is_null($this->_role)):
        // process
            $this->_role = Model_Roles::getInstance()->get($this->role);
        endif;
        return $this->_role;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ can @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pResource
     * @return boolean
     */
    public function can ($pResource) {
        if (!$this->get_role()):
            return FALSE;
        else:
            return Model_Acl::acl()->isAllowed($this->role, $pResource);
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ current_user_can @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pResource
     * @return boolean
     */
    public function current_user_can ($pResource) {
        if (self::current_user()):
            return self::current_user()->can($pResource);
        else:
            return Model_Acl::acl()->isAllowed('anonymous', $pResource);
        endif;
    }

}
