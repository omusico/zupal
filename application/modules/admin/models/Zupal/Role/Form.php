<?

class Zupal_Role_Form
extends Zupal_Form_Abstract
{

	public function __construct(Zupal_Roles $pRole = NULL)
	{

		$ini_path = dirname(__FILE__) . DS . 'Form.ini';
		$config = new Zend_Config_Ini($ini_path, 'fields');

		parent::__construct($config);

		$root = Zend_Controller_Front::getInstance()->getBaseUrl() . DS . 'admin' . DS . 'acl' . DS;

		if (is_null($pRole)) $pRole = new Zupal_Roles();
		$this->set_domain($pRole);

		if ($pRole->identity())
		{
			$this->setAction($root . 'rroleupdatevalidate');
		}
		else
		{
			$this->setAction($root  . 'roleaddvalidate');
			$this->submit->setLabel('Create Role');
		}

		$this->setMethod('post');
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function domain_fields ()
	{
		return array(
			'id', 'label', 'notes'
		);
	}

}