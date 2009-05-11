<?

class Zupal_Employees_Form
extends Zupal_Form_Abstract
{

	public function __construct(Zupal_Employee $pEmp = NULL)
	{

		
		$ini_path = dirname(__FILE__) . DS . 'Form.ini';
		$config = new Zend_Config_Ini($ini_path, 'fields');
		parent::__construct($config);

		if (!$pEmp):
			$pEmp = new Zupal_Employees();
		endif;

		$this->set_domain($pEmp);

		$this->addDisplayGroup(array('employee_name_first', 'employee_name_last', 'employee_email', 'employee_gender'),
			 'employee',
			array('legend' => 'employee', 'order' => 1));
		$this->addDisplayGroup(array('position', 'salary', 'status'),
			'Job',
			array('legend' => 'Position', 'order' => 2));
	}

	public function delete()
	{
		$logger = Zupal_Module_Manager::getInstance()->get('people')->logger();
		$logger->info('Employee ' . $this->identity() . ' deleted');
		$cache = Zupal_Bootstrap::$registry->cache;
		$cache->remove('employee_data');
		parent::delete();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function domain_fields ()
	{
		return array('node_id', 'person_id', 'position', 'salary', 'status', 'employee_name_first', 'employee_name_last', 'employee_email', 'employee_gender');
	}


	public function save()
	{
		$logger = Zupal_Module_Manager::getInstance()->get('people')->logger();
		parent::save();
		$logger->info('Employee ' . $this->get_domain()->identity() . ' saved');
		$cache = Zupal_Bootstrap::$registry->cache;
		$cache->remove('employee_data');
	}
}