<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * A Control is a link to another page -
 * usu. expressed in a link, button, or a link that looks like a button.
 *
 * @author daveedelhart
 */
abstract class Zupal_Control_Abstract {

	public abstract function __toString();

	const LINK = '<a href="%s" title="%s" class="%s">%s</a>';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ as_link @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function as_link ()
	{
		return sprintf(self::LINK, $this->url(), $this->get_title(), $this->get_class(), $this->get_label() );
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ url @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	* @return string
	*/
	public function url ()
	{
		$url = array(Zend_Controller_Front::getInstance()->getBaseUrl());
		if ($this->get_module()) $url[] = $this->get_module();
		$url[] = $this->get_controller();
		$url[] = $this->get_action();
		foreach($this->get_params() as $k => $v):
			$url[] = $k;
			$url[] = $v;
		endforeach;
		return join(DS, $url);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ class @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_class = null;
	/**
	 * @return class;
	 */

	public function get_class() { return $this->_class; }

	public function set_class($pValue) { $this->_class = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param array $pParams
	*/
	public function load ($pParams)
	{
		foreach($pParams as $k => $v):
			switch($k):
				case 'module':
					$this->set_module($v);
				break;

				case 'controller':
					$this->set_controller($v);
				break;

				case 'action':
					$this->set_action($v);
				break;

				case 'label':
					$this->set_label($v);
				break;

				case 'class':
					$this->set_class($v);
				break;

				case 'title':
					$this->set_title($v);
				break;

				default:
					$this->set_param($k, $v);
			endswitch;
		endforeach;

	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ title @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_title = null;
	/**
	 * @return string;
	 */
	public function get_title() { return $this->_title ? $this->_title : $this->get_label(); }

	public function set_title($pValue) { $this->_title = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ label @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_label = null;
	/**
	 * @return class;
	 */

	public function get_label() { return $this->_label; }

	public function set_label($pValue) { $this->_label = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ module @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_module = null;
	/**
	 * @return class;
	 */

	public function get_module() { return $this->_module; }

	public function set_module($pValue) { $this->_module = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ controller @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_controller = null;
	/**
	 * @return class;
	 */

	public function get_controller() { return $this->_controller; }

	public function set_controller($pValue) { $this->_controller = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ action @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_action = null;
	/**
	 * @return class;
	 */

	public function get_action() { return $this->_action; }

	public function set_action($pValue) { $this->_action = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ params @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_params = array();

	public function set_param($pName, $pValue)
	{
		$pValue = (string) $pValue;
		$this->_params[$pName] = htmlentities($pValue);
	}

	public function get_param($pID){ return $this->_params[$pID]; }

	public function get_params(){ return $this->_params; }
}
