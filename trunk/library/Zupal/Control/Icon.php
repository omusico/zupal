<?php

class Zupal_Control_Icon
extends Zupal_Image
{

	const ICON_PATH = '/img/icons/';
	const WIDTH = 21;
	const HEIGHT = 21;

	public function __construct($pPath, $pLabel = '', $pPlacement = self::PLACEMENT_HORIZONTAL)
	{
		$path = Zend_Controller_Front::getInstance()->getBaseUrl() . self::ICON_PATH . $pPath;

		parent::__construct($path, self::WIDTH, self::HEIGHT);
		$this->set_placement($pPlacement);
		
		$this->set_label($pLabel);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ label @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_label = 'null';
	/**
	 * @return class;
	 */

	public function get_label() { return $this->_label; }

	public function set_label($pValue) { $this->_label = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ placement @@@@@@@@@@@@@@@@@@@@@@@@ */

	const PLACEMENT_HORIZONTAL = 0;
	const PLACEMENT_VERTICAL = 1;
	const PLACEMENT_NONE = 2;
	private $_placement = 0;
	/**
	 * @return class;
	 */

	public function get_placement() { return $this->_placement; }

	public function set_placement($pValue) { $this->_placement = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __toString @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	const VI = '<div class="iconv">%s<div class="label">%s</div></div>';
	const HI = '<div class="iconh">%s<span class="label">%s</span></div>';
	const NI = '<div class="iconn">%s</div>';
	/**
	*
	* @return string
	*/
	public function __toString ()
	{
		if ($this->_label == ''):
			return parent::__toString();
		endif;

		switch($this->get_placement()):

			case self::PLACEMENT_HORIZONTAL:
				return sprintf(self::HI, parent::__toString(), $this->get_label());
			break;

			case self::PLACEMENT_VERTICAL:
				return sprintf(self::VI, parent::__toString(), $this->get_label());
			break;

			case self::PLACEMENT_NONE:
				return sprintf(self::NI, parent::__toString());
			break;

		endswitch;
	}
}