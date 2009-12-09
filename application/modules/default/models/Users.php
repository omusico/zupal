<?

class Model_Users
extends Model_Zupalatomdomain {

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


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ username @@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * @return string;
     */

    public function get_username() { return $this->username; }

    public function set_username($pValue) {
        if (!strcasecmp($pValue, $this->get_username())):
            return $this->username = $pValue;
        endif;

        $params = array('username' => $pValue);
        // find a record with this username;
        // by implication won't rematch this record becuase of previous test
        return ($this->findOne($params)) ? FALSE : $this->username = $pValue ;
   }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_password @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param <type> $pOld
 * @param <type> $pNew
 * @param <type> $pNew2
 * @return boolean
 */
   public function set_password ($pOld, $pNew, $pNew2) {
       if (md5($pOld) != $this->get_password()):
            return FALSE;
       elseif (!($pNew && ($pNew == $pNew2))):
            return FALSE;
       endif;

       $this->password = md5($pNew);
   }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_password @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
   /**
    *
    * @return string
    */
   public function get_password () {
       return $this->password;
   }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ for_atom_id @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
   /**
    *
    * @param <type> $pAtom_id
    * @return <type>
    */
   public function for_atom_id ($pAtom_id) {
       return $this->findOne(array('atomic_id' => $pAtom_id), 'ID desc');
   }
}
