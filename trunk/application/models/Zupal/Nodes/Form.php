<?php

abstract class Zupal_Nodes_Form
extends Zupal_Form_Abstract
{
	public function __construct(Zupal_Node_Abstract $pContent = NULL, $pOptions = NULL)
	{
		parent::__construct($pOptions);

		$this->set_domain($pContent);
		
		$this->removeDecorator('HtmlTag');
		
		$this->addElement('multiCheckbox', 'status', array('label' => 'Status', 'separator' => ''));
		$this->status->setMultiOptions(Zupal_Nodes::$STATUS_PHRASES);

		$this->addDisplayGroup(array('status'), 'node', array('legend' => 'Node', 'order' => 10));
		$this->getDisplayGroup('node')->removeDecorator('DtDdWrapper');

		$this->addElement('submit', 'submit', array('label' => 'Submit'));

		$this->addDisplayGroup(array('submit'), 'buttons', array( 'order' => 12));
		$this->getDisplayGroup('buttons')->removeDecorator('DtDdWrapper');

		$this->setMethod('post');
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function domain_fields ()
	{
		return array('title', 'text', 'node_id');
	}
}