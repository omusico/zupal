<?

class Content_AdminController
extends Zupal_Controller_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ createAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function preDispatch ()
	{
		parent::preDispatch();

		$menu = new Zupal_Menu();
		$item = new Zupal_Menu_Item('Content', 'content', 'index', 'index');
		$menu->set_item($item);
		$this->view->placeholder('breadcrumb')->set($menu);

	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ popupAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function popupAction ()
	{
        $this->_helper->layout->setLayout('popup');
		$this->view->content = new Zupal_Content($this->_getParam('id'));
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ deleteAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function deleteAction ()
	{
		$this->view->content = new Zupal_Content($this->_getParam('id'));
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function indexAction ()
	{

	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ createAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function createAction ()
	{
		 $this->view->form = new Zupal_Content_Form();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ dataAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function dataAction ()
	{
        $this->_helper->layout->disableLayout();
		$this->view->data = Zupal_Content::getInstance()->render_data(array(), 'email');
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ view @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function viewAction()
	{
		$content = Zupal_Content::getInstance()->get_by_node($this->_getParam('node'));
		$this->view->content = $content;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ editvalidateAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function editvalidateAction ()
	{

		$form = new Zupal_Content_Form();

		if ($form->isValid($this->_getAllParams())):
			$form->save();
			$this->_forward('view', NULL, NULL, array('node' => $form->get_domain()->nodeId()));
		else:
			$this->_forward('edit', NULL, NULL, array('reload' => 1, 'error' => 'Cannot save content'));
		endif;

	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ editAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function editAction ()
	{
		$include_paths = split(PS, get_include_path());

		$this->view->content = new Zupal_Content($this->_getParam('id'));
		$this->view->form = new Zupal_Content_Form($this->view->content);
		if ($this->_getParam('reload', FALSE)) $this->view->form->isValid($this->_getAllParams());
		$this->view->form->setAction(ZUPAL_BASEURL . DS . 'content/admin/editvalidate');
	}
}