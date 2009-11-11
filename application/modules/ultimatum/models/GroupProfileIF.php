<?

Interface Ultimatum_Model_GroupProfileIF
{

   const PROP_NETWORK = 'network';
   const PROP_OFFENSE = 'offense';
   const PROP_DEFENSE = 'defense';
   const PROP_GROWTH = 'growth';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ network_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function network_efficiency ($pString = FALSE);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ offense_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function offense_efficiency ($pString = FALSE);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ defense_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function defense_efficiency ($pString = FALSE);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ growth_efficiency @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function growth_efficiency ($pString = FALSE);


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ network_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function network_size ($pString = FALSE);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ offense_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function offense_size ($pString = FALSE);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ defense_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function defense_size ($pString = FALSE);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ growth_size @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function growth_size ($pString = FALSE);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ network_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function network_effect ($pString = FALSE);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ offense_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function offense_effect ($pString = FALSE);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ defense_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function defense_effect ($pString = FALSE);

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ growth_effect @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param boolean $pString = FALSE
 * @return scalar
 */
    public function growth_effect ($pString = FALSE);
    
}