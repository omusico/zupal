<?

class Model_Users
extends Zupal_Domain_Abstract {

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 * @see CPF_Formset_Domain::get_table_class()
 */
    public function tableClass () {
        return self::TABLE_CLASS;
    }
    const TABLE_CLASS = 'Model_DbTable_Users';
    
    /**
     * @see CPF_Formset_Domain::get()
     *
     * @param unknown_type $pID
     * @return CPF_Formset_Domain
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
    public function clear_current_user () {
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
}