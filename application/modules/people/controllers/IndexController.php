<?

class People_IndexController
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
		$this->view->form = new Zupal_People_Form();
		$this->view->people_form = new Zupal_People_Finder();
	}

	
}