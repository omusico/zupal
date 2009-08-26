<?

class Administer_Model_Modules
extends Zupal_Domain_Abstract {

/**
 * @see CPF_Formset_Domain::get()
 *
 * @param unknown_type $pID
 * @return Zupal_Domain_Abstract
 */
    public function get ($pID) {
        return new self($pID);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_Instance = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Zupal_Domain_Abstract
     */
    static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self(Zupal_Domain_Abstract::STUB);
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * @see CPF_Formset_Domain::get_table_class()
     *
     */
    public function tableClass () {
        return preg_replace('~_Table_~', '_', get_class($this));
    }

}