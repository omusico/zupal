<?
// note -- for_atomic_id is not implemented. 
abstract class Model_Zupalatomdomain
    extends Zupal_Domain_Abstract
    implements Model_ZupalatomIF
{
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ title @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_title() {
       return $this->get_atom()->get_title();
    }

    public function set_title($pValue){
       $this->get_atom()->set_title($pValue);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ lead @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_lead() {
       return $this->get_atom()->get_lead();
    }

    public function set_lead($pValue){
       $this->get_atom()->set_lead($pValue);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ content @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_content() {
        return $this->get_atom()->get_content();
    }

    public function set_content($pValue){
       $this->get_atom()->set_content($pValue);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_atomic_id @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

  //  public abstract function get_atomic_id ();

   // public abstract function set_atomic_id($pValue);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_model_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function get_model_class () {
        return $this->get_atom()->get_model_class();
    }

    public function set_model_class ($pValue) {
        return $this->get_atom()->set_model_class($pValue);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ status @@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * @return class;
     */

    public function get_status() { return $this->get_atom()->get_status(); }

    public function set_status($pValue) { $this->get_atom()->set_status($pValue); }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ author @@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * @return class;
     */

    public function get_author() { return $this->get_atom()->get_author(); }

    public function set_author($pValue) { $this->get_atom()->set_author($pValue); }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ atom @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 *
 * This method returns the atomic model that this class decorates. 
 */
    protected $_atom = NULL;
    function get_atom($pReload = FALSE) {
        if (!$this->get_atomic_id()):
            $this->_spawn_atom();
        elseif ($pReload || is_null($this->_atom)):
            $this->_atom = Model_Zupalatoms::getInstance()->get_atom($this->get_atomic_id());
            if (!$this->_atom):
                $this->_spawn_atom();
            endif;
        endif;
        return $this->_atom;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _spawn_atom @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function _spawn_atom () {
        $this->_atom = new Model_Zupalatoms();
        $this->_atom->set_model_class(get_class($this));
        $this->set_atomic_id($this->_atom->get_atomic_id());
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_bonds @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ /*/
    /**
     *
     * @param variant $pType = NULL
     * @return Model_Zupalbonds[]
     */
    public function get_bonds ($pType = NULL,
        Model_ZupalatomIF $pTarget = NULL,
        Model_ZupalatomIF $bond_atom = NULL) {
        return Model_Zupalbonds::getInstance()->get_bonds_from($this, $pType);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ bond @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 *
 * @param string $pType
 * @param Model_ZupalatomIF $pTarget
 * @param bool $pSingular
 * @param Model_ZupalatomIF $bond_atom
 * @return Model_Zupalbonds
 */
    public function bond_to ($pType,
        Model_ZupalatomIF $pTarget,
        $pSingular = TRUE,
        Model_ZupalatomIF $bond_atom = NULL) {
        //@TODO: enforce singularity

        $bond = new Model_Zupalbonds();
        $bond->to_atom = $pTarget->get_atomic_id();
        $bond->to_model_class = $pTarget->get_model_class();

        $bond->from_atom = $this->get_atomic_id();
        $bond->from_model_class = $this->get_model_class();

        $bond->type = $pType;
        if ($bond_atom):
            $bond->bond_atom = $bond_atom->get_atomic_id();
        endif;

        $bond->save();

        return $bond;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ unbond @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param id | Model_ZupalatomIF $pTarget
     * @return void
     */
    public function unbond ($pTarget, $pType = NULL,
        Model_ZupalatomIF $bond_atom = NULL) {
        throw new Exception(__METHOD__ . ' not implemented');
    }  

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function save () {
        $this->get_atom()->save();
        parent::save();
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ delete @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void;
     */
    public function delete () {
        try {
            $this->get_atom()->delete();
        }
        catch (Exception $e)
        {
            error_log(print_r($e, 1));
        }
        parent::delete();
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ add_ion @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pKey
     * @param string $pValue = NULL
     * @return Model_Zupalions
     */
    public function add_ion ($name, $value = NULL) {
        if (is_array($name)):
            extract($name);
        endif;

        $params = array('name' => $name, 'value' => $value, 'atomic_id' => $this->get_atomic_id());

        if (!$ion = Model_Zupalions::getInstance()->findOne($params)):
            $ion = Model_Zupalions::getInstance()->get(NULL, $params);
            $ion->save();
        endif;
        return $ion;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_ion @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pName
     * @param boolean $pValue
     *     determines the return type -- domain or scalar.
     * @return Model_Zupalions
     */
    public function get_ion ($pName, $pValue = FALSE) {
        $params = array('atomic_id' => $this->get_atomic_id(), 'name' => $pName);
        $ion = Model_Zupalions::getInstance()->findOne($params);
        if ((!$ion) || (!$ion->isSaved())):
            return NULL;
        elseif ($pValue):
            return $ion->value;
        else:
            return $ion;
        endif;
    }

}