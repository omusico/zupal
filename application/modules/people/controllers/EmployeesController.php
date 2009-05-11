<?php

class People_EmployeesController
extends Zupal_Controller_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ seventhAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function seventhAction ()
	{
		$es = Zupal_Employees::getInstance();

		$select = $es->table()->select()
		->order('salary DESC')
		->limit(1, 6);

		$this->view->select = $select;
		$row = $es->table()->getAdapter()->fetchRow($select->assemble());
		$this->view->employee = new Zupal_Employees($row['eid']);

	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function indexAction ()
	{
		$this->view->new_form = new Zupal_Employees_Form();

		$this->view->new_form->setAction(ZUPAL_BASEURL . DS . join(DS, array('people','employees', 'addvalidate')));
	}

	public function addvalidateAction()
	{
		$this->view->new_form = new Zupal_Employees_Form();
		$this->view->new_form->setAction(ZUPAL_BASEURL . DS . join(DS, array('people','employees', 'addvalidate')));

		if ($this->view->new_form->isValid($this->_getAllParams())):
			$this->view->new_form->save();
			$this->_forward('view', NULL, NULL, array('message' => 'Employee Created', 'id' => $this->view->new_form->get_domain()->identity()));
		else:
			$this->_forward('add', NULL, NULL, array('error' => 'Cannot create employee', 'reload' => 1));
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ viewAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function viewAction ()
	{
		$this->view->employee = new Zupal_Employees($this->_getParam('id'));
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ dataAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function dataAction ()
	{
        $this->_helper->layout->disableLayout();
		$this->view->data = Zupal_Employees::getInstance()->render_data(array(), 'salary');
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ addAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function addAction ()
	{
		$this->view->new_form = new Zupal_Employees_Form();
	}
}