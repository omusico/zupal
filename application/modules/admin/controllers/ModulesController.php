<?php 

class Admin_ModulesController extends Zupal_Controller_Abstract
{
	public function indexAction() 
	{
		$moduleConfigs = array();
	
		Zupal_Module_Manager::getInstance()->update_database();
		$this->view->modules = Zupal_Module_Manager::getInstance()->get_all();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ tabledefAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function tabledefAction ()
	{
		$adapter = Zend_Db_Table_Abstract::getDefaultAdapter();
		$this->view->tables = $adapter->fetchCol('show tables;');
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ tableclassAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function tableclassAction ()
	{
        $this->_helper->layout->disableLayout();
		$table_def = Zend_Db_Table_Abstract::getDefaultAdapter()->describeTable($this->_getParam('table'));
		$td = $table_def;
		
		foreach($td as $c => $row) 
		{
			if ($td[$c]['IDENTITY']):
				 $name = $td[$c]['COLUMN_NAME'];
				  $td[$c]['COLUMN_NAME'] = "<b><u>$name</u></b>";
				  $this->view->id_field = $name;
			endif;
			$this->view->table_name = $td[$c]['TABLE_NAME'];
			
			unset($td[$c]['SCHEMA_NAME']);
			unset($td[$c]['TABLE_NAME']);
			unset($td[$c]['COLUMN_POSITION']);
			unset($td[$c]['PRIMARY_POSITION']);
			unset($td[$c]['PRIMARY']);
			unset($td[$c]['IDENTITY']);
		}
		$this->view->table_def_display = $td;
		$this->view->table_def = $table_def;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ edit @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public function editAction()
	{
		$module = new Zupal_Modules($this->_getParam('name'));
		$this->view->module = $module;
		$this->view->form = new Zupal_Modules_Form($module);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ editvalidateAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function editvalidateAction ()
	{
		$form = new Zupal_Modules_Form(Zupal_Modules::module($this->_getParam('name')));
		if ($form->isValid($this->_getAllParams())):
			$form->save();
			$this->_forward('edit', NULL, NULL, array('message' => $form->get_domain()->name . ' updated'));
		endif;
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ formmakerAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function formmakerAction ()
	{
		$this->view->tables = array();
		foreach(Zupal_Nodes::getInstance()->table()->getAdapter()->fetchAll('SHOW TABLES')
			as $titem):
			$this->view->tables[] = array_pop($titem);
		endforeach;
	}

	public function formmakervalidateAction()
	{
        $this->_helper->layout->disableLayout();
		$this->view->table = $this->_getParam('table');
		$this->view->detail = (Zupal_Nodes::getInstance()->table()->getAdapter()->fetchAll('DESCRIBE ' . $this->view->table));
	}

	public function installAction() {}
	
	public function uninstallAction() 
	{
		$request = $this->getRequest();
		if($request->getParam('moduleName',null) != null) 
		{
			$moduleName = $request->getParam('moduleName');
			$message = sprintf("Module: %s uninstalled.", $moduleName);
			Zend_Debug::dump($message, "Uninstall Message:");
			exit();
			$this->_helper->flashMessenger($message);
		}
	}
	
	public function enableAction() {}
	
	public function disableAction() {}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ viewAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function viewAction ()
	{
		$this->view->item = Zupal_Module_Manager::getInstance()->get($this->_getParam('name'));
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ dataAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function dataAction ()
	{
        $this->_helper->layout->disableLayout();
		$this->view->data = Zupal_Modules::getInstance()->render_data(array(), 'name');
	}

}