<?php

class Zupal_Places_Form
extends Zend_Form
{
	public function __construct(Zupal_Places $pPlace = NULL)
	{		
		$ini_path = dirname(__FILE__) . DS . 'form.ini';
		$config = new Zend_Config_Ini($ini_path, 'fields');

		parent::__construct($config);
		
		$this->load_countries();
		$this->setMethod('post');

		if (is_null($pPlace)) {
			$pPlace = new Zupal_Places();
			$this->set_place($pPlace);
		}
		else
		{
			$this->set_place($pPlace);
			$this->place_to_fields();
		}
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_countries @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return void
	*/
	public function load_countries ()
	{
		$this->country->setMultiOptions(Zupal_Places_Countries::as_list());
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ place @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_place = null;
	/**
	 * @return Zupal_Places;
	 */
	public function get_place() {
		if (is_null($this->_place))
		{
			$this->_place = new Zupal_Places();
			$this->fields_to_place();
		}
		
		return $this->_place; 
	}

	public function set_place($pValue) { $this->_place = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ place_to_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * Note -- to prevent recursion this method does NOT check the existence of _place.
	 */
	public function fields_to_place()
	{
		$this->get_place()->setAddress(array($this->addr->getValue(), $this->addr2->getValue()));
		$this->get_place()->set_name($this->name->getValue());
		$this->get_place()->setCity($this->city->getValue());
		$this->get_place()->setState($this->state->getValue());
		$this->get_place()->setCountry($this->country->getValue());
		$this->get_place()->setPostalcode($this->postalcode->getValue());
	}
	
	/**
	*
	* @return void
	*/
	public function place_to_fields()
	{
		$this->name->setValue($this->get_place()->get_name());
		$this->addr->setValue($this->get_place()->getAddress()->addr);
		$this->addr2->setValue($this->get_place()->getAddress()->addr2);
		$this->city->setValue($this->get_place()->getCity()->get_value());
		$this->state->setValue($this->get_place()->getState()->get_value());
		$this->postalcode->setValue($this->get_place()->getPostalcode()->get_value());

	}
	
}