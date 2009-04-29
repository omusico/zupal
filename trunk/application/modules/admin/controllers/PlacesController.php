<?php 

class Admin_PlacesController extends Zend_Controller_Action
{


	public function preDispatch()
	{
		parent::preDispatch();

		$menu = new Zupal_Menu();
		$item = new Zupal_Menu_Item('Admin', 'admin', 'index', 'index');
		$menu->set_item($item);
		$item = new Zupal_Menu_Item('Places', 'places', 'index', 'index');
		$menu->set_item($item);
		$this->view->placeholder('breadcrumb')->set($menu);

	}

	public function indexAction()
	{
		$this->view->places = Zupal_Places::getInstance()->findAll('country');
	}

	public function newvalidateAction()
	{
		$form = new Zupal_Places_Form();
		if ($form->isValid($this->_getAllParams())):
			$form->fields_to_place();
			$form->get_place()->save();
			$this->_forward('view', NULL, NULL, array('id' => $form->get_place()->identity()));
		else:
			$this->_forward('new', NULL, NULL, array('retry' => 1, 'error' => 'Your location could not be saved.'));
		endif;
	}

	public function viewAction()
	{
		$this->view->place = new Zupal_Places($this->_getParam('id', $this->_getParam('place_id')));
	}
}