<?

abstract class Model_Zupalatomdomain
extends Zupal_Domain_Abstract
implements Model_ZupalatomIF
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ title @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_title(){
        return $this->get_atom($this->get_atomic_id())->get_title();
    }

    public function set_title($pValue);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ lead @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_lead(){
        return $this->get_atom($this->get_atomic_id())->get_lead();
    }

    public function set_lead($pValue);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ body @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_body(){
        return $this->get_atom($this->get_atomic_id())->get_body();
    }

    public function set_body($pValue);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_atomic_id @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public abstract function get_atomic_id ();

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_model_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function get_model_class (){
        return $this->get_atom($this->get_atomic_id())->get_model_class();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_atom @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param $pAtomic_id
     * @return Model_Zupalatom
     */
    public function get_atom ($pAtomic_id, $pVersion = NULL){
        return Model_Zupalatoms::getInstance()->get_atom($pAtomic_id, $pVersion);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_bonds @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param variant $pType = NULL
     * @return Model_Zupalbonds[]
     */
    public function get_bonds ($pType = NULL) {

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
        Model_ZupalatomIF $bond_atom = NULL);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ unbond @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param id | Model_ZupalatomIF $pTarget
     * @return void
     */
    public function unbond ($pTarget, $pType = NULL,
        Model_ZupalatomIF $bond_atom = NULL);


}