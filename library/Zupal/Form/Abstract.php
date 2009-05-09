<?php

abstract class Zupal_Form_Abstract
extends Zend_Form
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_domain = NULL;
/**
  * @return Zupal_Domain_IDomain
  */
	public function get_domain()
	{
		return $this->_domain;
	}

	public function set_domain(Zupal_Domain_IDomain $value)
	{
		$this->_domain = $value;
		$this->domain_to_fields();
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ translation @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 *
 * If your form has more complex relationships,
 * override these methods to allocate field/domain values
 *
 * @param Zupal_Domain_IDomain $pObject
 * @param array $pFields
 */
	protected function domain_to_fields(array $pFields = NULL)
	{
		$object = $this->get_domain();

		if (!is_array($pFields)):
			$pFields = $this->domain_fields();
		endif;

		foreach($pFields as $field):
			$element = $this->getElement($field);
			$element->setValue($object->$field);
		endforeach;
	}

	protected function fields_to_domain(array $pFields = NULL)
	{
		$object = $this->get_domain();

		if (!is_array($pFields)):
			$pFields = $this->domain_fields();
		endif;

		foreach($pFields as $field):
			$element = $this->getElement($field);
			$object->$field = $element->getValue();
		endforeach;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public abstract function domain_fields ();

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return void
	*/
	public function save ()
	{
		$this->fields_to_domain();
		$this->get_domain()->save();
	}
}