<?php

class Zupal_Menu
{

	public function __construct($pTitle = '', $pData = NULL)
	{
		$this->set_title($pTitle);
		
		if ($pData && is_array($pData))
		{
			foreach($pData as $k => $v)
			{
				$this->set_item($pData, $k);
			}
		}
	}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ depth @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_depth = 1;
	/**
	 * @return class;
	 */

	public function get_depth() { return $this->_depth; }

	public function set_depth($pDepth) {
		$this->_depth = max(1, (int) $pDepth);
	}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ class @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_class = 'menu';
	/**
	 * @return string;
	 */

	public function get_class() { return $this->_class; }

	public function set_class($pValue) { $this->_class = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ title @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_title = null;
	/**
	 * @return string;
	 */

	public function get_title() { return $this->_title; }
	public function set_title($pValue) { $this->_title = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ items @@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	 * items are serial links; they can be strings, string-renderable objects, or even other menus.
	 *
	 */
	private $_items = array();

	public function set_item($pValue, $pID = NULL)
	{
		if (is_null($pID)):
		array_push($this->_items, $pValue);
		else:
		$this->_items[$pID] = $pValue;
		endif;
	}

	public function get_item($pID){ return $this->_items[$pID]; }

	public function get_items(){ return $this->_items; }

	public function __toString()
	{
		ob_start();
?>
<!-- begin menu -->
<div class="<?= $this->get_class() ?>">
<? if ($this->get_title()): ?>
<h<?= $this->get_depth()?>><?= $this->get_title() ?></h<?= $this->get_depth() ?>>
<? endif; ?>
<ol>
<? foreach($this->get_items() as $item): ?>
	<li><?= $item ?></li>
<? endforeach; ?>
</ol>
</div>
<!-- end menu -->
<?
		return ob_get_clean();
	}


}