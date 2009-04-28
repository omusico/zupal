<?php

abstract class Zupal_Nodes_Form
extends Zend_Form
{
	public function __construct(Zupal_Node_Abstract $pContent = NULL)
	{
		parent::__construct();
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
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ content @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_content = null;
	/**
	 * @return Zupal_Node_Abstract;
	 */
	public function get_content(){ return $this->_content; }
	public function set_content(Zupal_Node_Abstract $pValue) { $this->_content = $pValue; }

}