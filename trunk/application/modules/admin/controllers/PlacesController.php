<?php 

class Admin_PlacesController extends Zupal_Controller_Abstract
{


	public function preDispatch()
	{
		parent::preDispatch();

		$menu = new Zupal_Menu();
		$item = new Zupal_Menu_Item('Admin', 'admin', 'index', 'index');
		$menu->set_item($item);
		$item = new Zupal_Menu_Item('Places', 'admin', 'places', 'index');
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

	public function editAction()
	{
		$place = Zupal_Places::getInstance()->get($this->_getParam('id'));
		$this->view->place = $place;
		$this->view->form = new Zupal_Places_Form($place);
		$this->view->form->submit->setLabel('Update Place');
		if ($this->_getParam('reload')) $this->view->form->isValid($this->_getAllParams());
		$action = Zend_Controller_Front::getInstance()->getBaseUrl() . DS . join(DS, array('admin', 'places', 'editvalidate'));
		$this->view->form->setAction($action);
	}

	public function editvalidateAction()
	{
		$place = Zupal_Places::getInstance()->get($this->_getParam('place_id'));
		$this->view->place = new Zupal_Places($place);
		$this->view->form = new Zupal_Places_Form($place);
		if($this->view->form->isValid($this->_getAllParams())):
			$this->view->form->fields_to_place();
			$this->view->form->get_place()->save();
			$this->_forward('view', NULL, NULL, array('message' => 'Place Upadated', 'id' => $this->view->form->get_place()->identity()));
		else:
			$this->_forward('edit', NULL, NULL, array('error' => 'Could not save place', 'reload' => 1,  'id' => $this->view->form->get_place()->identity()));
		endif;
	}
}