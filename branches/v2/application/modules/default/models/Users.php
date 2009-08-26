<?

class Model_Users
extends Zupal_Domain_Abstract {

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 * @see CPF_Formset_Domain::get_table_class()
 */
    public function tableClass () {
        return preg_replace('~^Model_~', 'Model_Table_', get_class($this));
    }

    /**
     * @see CPF_Formset_Domain::get()
     *
     * @param unknown_type $pID
     * @return CPF_Formset_Domain
     *
     */
    public function get ($pID) {
        return new self($pID);
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
        if ($pReload || is_null(self::$_current_user)):
        // process
            self::$_current_user = $value;
        endif;
        return $this->_current_user;
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