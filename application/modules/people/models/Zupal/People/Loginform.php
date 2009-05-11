<?

class Zupal_People_Loginform
extends Zupal_Form
{

	public function __construct()
	{

		$ini_path = dirname(__FILE__) . DS . 'Loginform.ini';
		$config = new Zend_Config_Ini($ini_path, 'fields');

		parent::__construct($config);

		$root = Zend_Controller_Front::getInstance()->getBaseUrl() . DS . 'people' . DS . 'item';

		if (is_null($pPeople)) $pPeople = new Zupal_People();
		$this->set_domain($pPeople);

		if ($pPeople->identity())
		{
			$this->setAction($root . DS . 'updatevalidate');
		}
		else
		{
			$this->setAction($root . DS . 'addvalidate');
			$this->submit->setLabel('Create Person');
		}

		$this->setMethod('post');
	}

}