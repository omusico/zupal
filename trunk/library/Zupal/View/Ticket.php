<?php

class Zupal_View_Ticket
implements Zupal_View_Ticket_ITicket
{
	public function __construct($pItem)
	{
		$this->set_item($pItem);
	}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ title @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_title = null;
	/**
	 * @return class;
	 */

	public function get_title() { return $this->_title; }

	public function set_title($pValue) { $this->_title = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ value @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_values = array();
		
	public function set_value($pID, $pValue)
	{
		$this->_values[$pID] = $pValue;
	}
		
	public function get_value($pID){ return $this->_values[$pID]; }		
	public function get_values(){ return $this->_values; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ action @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_actions = array();

	public function set_action($pID, $pProps)
	{
		$this->_actions[$pID] = $pProps;
	}

	public function get_action($pID){ return $this->_actions[$pID]; }
	public function get_actions(){ return $this->_actions; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ render @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function render()
	{
		ob_start();
		?>
<div class="ticket">
	<h2><?= $this->get_title() ?></h2>

<dl>
<? foreach($this->get_values() as $key => $prop): ?>
	<dt><?= $key ?></dt>
	<dd><?= $prop ?></dd>
<? endforeach; ?>
</dl>

<? if (count($this->_actions)): ?>
<dl class="action">
	<? foreach($this->_actions as $label => $action): ?>
	<? if (is_object($action)): ?>
		<?= $action ?>
	<? else: ?>
		<a href="<?= $action ?>"><?= $label ?></a>
	<? endif; ?>
	<? endforeach; ?>
</dl>
<? endif; ?>
</div>
<?
		$out = ob_get_clean();
		return $out;
	}
}