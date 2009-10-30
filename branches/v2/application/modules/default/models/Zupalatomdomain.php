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


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ atom @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 *
 * This method returns the atomic model that this class decorates. 
 */
    protected $_atom = NULL;
    function get_atom($pReload = FALSE) {
        if (!$this->get_atomic_id()):
            $this->_atom = new Model_Zupalatoms();
            $this->_atom->save();
            $this->set_atomic_id($this->_atom->get_atomic_id());
        elseif ($pReload || is_null($this->_atom)):
        // process
            $this->_atom = Model_Zupalatoms::getInstance()->get_atom($this->get_atomic_id());
        endif;
        return $this->_atom;
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
}