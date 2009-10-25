<?
// note -- for_atomic_id is not implemented. 
abstract class Model_Zupalatomdomain
 extends Zupal_Domain_Abstract
 //implements Model_ZupalatomIF
{

    abstract public function for_atom_id($pAtom_id);
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ title @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_title() {
       return $this->get_atom($this->get_atomic_id())->get_title();
    }

    public function set_title($pValue){
       $this->get_atom($this->get_atomic_id())->set_title($pValue);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ lead @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_lead() {
       return $this->get_atom($this->get_atomic_id())->get_lead();
    }

    public function set_lead($pValue){
       $this->get_atom($this->get_atomic_id())->set_lead($pValue);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ content @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_content() {
        return $this->get_atom($this->get_atomic_id())->get_content();
    }

    public function set_content($pValue){
       $this->get_atom($this->get_atomic_id())->set_content($pValue);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_atomic_id @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public abstract function get_atomic_id ();

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_model_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function get_model_class () {
        return $this->get_atom($this->get_atomic_id())->get_model_class();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_atom @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param $pAtomic_id
     * @return Model_Zupalatom
     */
    public function get_atom ($pAtomic_id, $pVersion = NULL) {
        return Model_Zupalatoms::getInstance()->get_atom($pAtomic_id, $pVersion);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_bonds @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ /*/
    /**
     *
     * @param variant $pType = NULL
     * @return Model_Zupalbonds[]
     */
    public function get_bonds ($pType = NULL) {
        return Model_Zupalbonds::getInstance()->get_bonds_from($this, $pType);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ bond @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pType, $pSingular = TRUE, $pTarget
     * @return Model_Zupalbonds
     */
    public function bond_to ($pType,
        Model_ZupalatomIF $pTarget,
        $pSingular = TRUE,
        Model_ZupalatomIF $bond_atom = NULL) {
        throw new Exception(__METHOD__ . ' not implemented');
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

}