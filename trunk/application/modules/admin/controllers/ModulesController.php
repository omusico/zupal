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
		$database = $this->_getParam('database', '');

        $this->_helper->layout->disableLayout();
		if ($database):
			$adapter = Zupal_Module_Manager::getInstance()->database($database);
		else:
			$adapter = Zend_Db_Table_Abstract::getDefaultAdapter();
		endif;
		$table_def = $adapter->describeTable($this->_getParam('table'));
		$td = $table_def;
		
		foreach($td as $c => $row) 
		{
			if ($td[$c]['IDENTITY']):
				 $name = $td[$c]['COLUMN_NAME'];
				  $td[$c]['COLUMN_NAME'] = "<b><u>$name</u></b>";
				  $this->view->id_field = $name;
			endif;
			$this->view->table_name = $table_name = $td[$c]['TABLE_NAME'];
			
			unset($td[$c]['SCHEMA_NAME']);
			unset($td[$c]['TABLE_NAME']);
			unset($td[$c]['COLUMN_POSITION']);
			unset($td[$c]['PRIMARY_POSITION']);
			unset($td[$c]['PRIMARY']);
			unset($td[$c]['IDENTITY']);
		}

		$connection = $adapter->getConnection();

		$result = $connection->query("SHOW CREATE TABLE `$table_name`");
		$this->view->create_sql = array_pop($result->fetch_row());

		$this->view->table_def_display = $td;
		$this->view->table_def = $table_def;
		$table_class = 'Zupal_Table_' .
				ucwords(
					preg_replace('~(_.)~e', "strtoupper('\\0')",
					preg_replace(
				'~^Zupal_~i', '',
				$table_name)
			));

		$this->view->table_class_name = $table_class;
		$this->view->db_name = $database;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ tablewriteAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function tablewriteAction ()
	{
        $this->_helper->layout->disableLayout();

		$table_class_name = $this->_getParam('table_class_name');
		$class_file = stripslashes($this->_getParam('class_file'));
		$module = $this->_getParam('table_module');

		$file_path = ZUPAL_MODULE_PATH . DS . $module . DS . 'models' . DS . str_replace('_', DS, $table_class_name) . '.php';

		$this->view->body = $class_file;
		$this->view->file_path = $file_path;

		if (file_exists($file_path)):
			$this->view->status = 'Table Class Updated';									
		else:
			$this->view->status = 'Table Class Created';
		endif;

		if (!is_dir(dirname($file_path))):
			mkdir(dirname($file_path), 0775, TRUE);
		endif;
		
		file_put_contents($file_path, $class_file);
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