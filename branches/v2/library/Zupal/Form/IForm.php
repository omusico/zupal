<?

interface Zupal_Form_IForm
{

	/**
	 * Sets the action of the form.
	 * If an object is passed must be resolvable to a string (i.e., have __toString();
	 * @param var $pAction
	 */
	public function set_target($pAction);

	/**
	 * Note that the model passed in, the system of record for field values,
	 * and the model returned from get_model are not required to be one and the same.
	 * set_model may pass values to an internal system of record,
	 * and by implication, get_model may return a different object (or an array, or stdClass).
	 * That is there is no enforced binding of the domain or an ORM system to the form interface. 
	 *
	 * @param boolean $pAutoLoad
	 */
	public function get_model($pAutoLoad = TRUE);
	/**
	 * @param var $pModel -- an array or object. If an object must have a toArray() method.
	 */
	public function set_model($pModel);

	public function get_field_value($pField);
	public function set_field_value($pField, $pValue);
	/**
	 *
	 * @param string $pField
	 * @param string $pType
	 * @param var $pParams;
	 *   note if scalar, transformed into an araay ('value' => $pParams);
	 */
	public function define_field($pField, $pLabel, $pType, $pParams = NULL);

	/**
	 * renders the form.
	 * @return string;
	 */
	public function __toString();
}