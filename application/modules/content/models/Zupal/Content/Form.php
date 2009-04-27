<?php

class Zupal_Content_Form
extends Zend_Form
{
	public function __construct(Zupal_Content $pContent = NULL)
	{
		$ini_path = dirname(__FILE__) . DS . 'form.ini';
		$config = new Zend_Config_Ini($ini_path, 'fields');
		parent::__construct($config);

		$root = Zend_Controller_Front::getInstance()->getBaseUrl() . DS . 'content' . DS . 'item';

		if ($pContent)
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
	}
}