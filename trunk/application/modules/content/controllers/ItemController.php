<?php

class Content_ItemController
extends Zupal_Controller_Abstract
{

	public function preDispatch()
	{
		parent::preDispatch();

		$menu = new Zupal_Menu();
		$item = new Zupal_Menu_Item('Content', 'content', 'index', 'index');
		$menu->set_item($item);
		$this->view->placeholder('breadcrumb')->set($menu);

	}

	public function addAction()
	{
		if (!$this->_getParam('reload_content', FALSE))
		{
			$this->view->form = new Zupal_Content_Form();
		}
	}

	public function addvalidateAction()
	{
		$this->view->form = new Zupal_Content_Form();
		if ($this->view->form->isValid($this->_getAllParams()))
		{
			$this->view->form->fields_to_content();
			$this->view->form->get_content()->save();
			$saved = sprintf('&quot;%s&quot; saved.', $this->view->form->get_content()->title());
			$this->_forward('view', NULL, NULL, array('node' => $this->view->form->get_content()->nodeId(), 'message' => $saved));
		}
		else
		{
			$this->_forward('add', NULL, NULL, array('error' => 'Cannot save content', 'reload_content' => 1));
		}
	}

	public function viewAction()
	{
		$content = Zupal_Content::getInstance()->get_by_node($this->_getParam('node'));
		$this->view->content = $content;
	}
}