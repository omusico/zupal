<?php

class Zupal_Content_Form
extends Zupal_Nodes_Form
{
	public function __construct(Zupal_Content $pContent = NULL)
	{
		if (is_null($pContent)) $pContent = new Zupal_Content();
		
		$ini_path = dirname(__FILE__) . DS . 'Form.ini';
		$config = new Zend_Config_Ini($ini_path, 'fields');
		$elements = $config->elements->toArray();
		
		parent::__construct($pContent);

		$this->addElements($elements);

		$this->addDisplayGroup(array_keys($elements), 'content', array('order' => -1, 'legend' => 'detail', 'style' =>"width: 400px"));
		$this->getDisplayGroup('content')->removeDecorator('DtDdWrapper');

		$root = Zend_Controller_Front::getInstance()->getBaseUrl() . DS . 'content' . DS . 'item';

		if ($pContent->identity())
		{
			$this->set_content($pContent);
			$this->content_to_fields();
			$this->setAction($root . DS . 'updatevalidate');
		}
		else
		{
			$this->setAction($root . DS . 'addvalidate');
			$this->submit->setLabel('Create Content');
			$this->set_content(new Zupal_Content());
		}
		$this->setMethod('post');
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ content @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_content = null;
	/**
	 * @return Zupal_Content;
	 */
	public function get_content() {
		if (is_null($this->_content))
		{
			$this->_content = new Zupal_Content();
			$this->fields_to_content();
		}
		
		return $this->_content; 
	}

	/**
	 * Note -- to prevent recursion this method does NOT check the existence of _content.
	 */
	public function fields_to_content()
	{
		$this->_content->node_id = $this->node_id->getValue();
		$this->_content->title = $this->title->getValue();
		$this->_content->text = $this->text->getValue();
		$this->_content->node()->set_status($this->status->getValue());
	}

	public function set_content($pValue) { $this->_content = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ content_to_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return void
	*/
	public function content_to_fields ()
	{
		$this->title->setValue( $this->get_content()->title());
		$this->text->setValue( $this->get_content()->text());
		$this->node_id->setValue( $this->get_content()->nodeId());
		$statuses = $this->get_content()->node()->status(1);
		$this->status->setValue($statuses);

	}
}