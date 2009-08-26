<?

class Model_Roles
extends Zupal_Domain_Abstract {

const ROLE_ADMIN = 'admin';
const ROLE_ANONYMOUS = 'anonymous';

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


}