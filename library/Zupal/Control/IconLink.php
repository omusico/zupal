<?php

class Zupal_Control_IconLink
extends Zupal_Control_Abstract
{

	public function __construct($pIconPath, $pLabel, $pParams)
	{

		parent::load($pParams);

		$icon = new Zupal_Control_Icon($pIconPath, $pLabel);

		$this->set_title($pLabel);

		$this->set_icon($icon);

	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ icon @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_icon = NULL;
	/**
	  * @return Zupal_Control_Icon
	  */
	public function get_icon(){ return $this->_icon; }

	public function set_icon(Zupal_Control_Icon $value){ $this->_icon = $value; }

	public function get_label(){ return $this->get_icon(); }

	public function __toString()
	{
		return $this->as_link();
	}
}