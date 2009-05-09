<?

class People_ItemController
extends Zupal_Controller_Abstract
{

	public function preDispatch()
	{
		parent::preDispatch();

		$menu = new Zupal_Menu();
		$item = new Zupal_Menu_Item('People', 'people', 'index', 'index');
		$menu->set_item($item);
		$this->view->placeholder('breadcrumb')->set($menu);

	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function indexAction ()
	{
		$this->people = new Zupal_People(Zupal_Domain_Abstract::STUB);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ addAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function addAction ()
	{
		$this->view->form = new Zupal_People_Form();

		if ($this->_getParam('reload')):
			$this->view->form->isValid($this->_getAllParams());
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ viewAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function viewAction ()
	{
		$this->view->person = new Zupal_People($this->_getParam('id'));
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ addvaidateAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function addvalidateAction ()
	{
		$add_form = new Zupal_People_Form();
		if ($add_form->isValid($this->_getAllParams())):
			$add_form->save();
			$this->_forward('view', NULL, NULL, array('messge' => 'Person Saved', 'id' => $add_form->get_domain()->identity()));
		else:
			$this->_forward('add', NULL, NULL, array('error' => 'Cannot save person', 'reload' => 1));
		endif;
	}
}