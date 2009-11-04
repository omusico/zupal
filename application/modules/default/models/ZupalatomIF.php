<?

interface Model_ZupalatomIF
extends Zupal_Domain_IDomain
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ title @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_title();

    public function set_title($pValue);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ lead @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_lead();

    public function set_lead($pValue);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ content @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_content();

    public function set_content($pValue);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_atomic_id @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_atomic_id ();
    
    public function set_atomic_id($pValue);


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ author @@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * @return Model_Users;
     */

    public function get_author();

    public function set_author($pValue);
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ status @@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * @return class;
     */

    public function get_status();

    public function set_status($pValue);


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_model_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function get_model_class ();

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ for_atom_id @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * This class returns the model that the atom reflects;
     * some atoms are self-reflective (don't refer to another domain class)
     * but others point towards another domain class that reflect the atomic class
     *
     * @param int $pAtom_id
     * @return Model_ZupalatomIF
     */
     
    public function for_atom_id ($pAtom_id);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_bonds @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param variant $pType = NULL
     * @return Model_Zupalbonds[]
     */
    public function get_bonds ($pType = NULL,
        Model_ZupalatomIF $pTarget = NULL,
        Model_ZupalatomIF $bond_atom = NULL
        );

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ bond @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ *
    /**
     *
     * @param string $pType, $pSingular = TRUE, $pTarget
     * @return Model_Zupalbonds
     */
    public function bond_to ($pType,
        Model_ZupalatomIF $pTarget,
        $pSingular = TRUE,
        Model_ZupalatomIF $bond_atom = NULL);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ unbond @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ *
    /**
     *
     * @param id | Model_ZupalatomIF $pTarget
     * @return void
     */
    public function unbond ($pTarget, $pType = NULL,
        Model_ZupalatomIF $bond_atom = NULL);


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ add_ion @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pKey
     * @param string $pVallue
     * @return Model_Zupalions
     */
    public function add_ion ($pKey, $pValue = NULL);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_ion @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @param string $pName
     * @param boolean $pValue
     *
     */
    public function get_ion ($pName, $pValue = FALSE);
}