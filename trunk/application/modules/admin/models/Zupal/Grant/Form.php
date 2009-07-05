<?

class Zupal_Grant_Form
extends Zend_Form
{

	public function __construct($pRes = NULL, $pRole = NULL)
	{

		$ini_path = dirname(__FILE__) . DS . 'Form.ini';
		$config = new Zend_Config_Ini($ini_path, 'fields');

		parent::__construct($config);

		if ($pRes instanceof Zupal_Resources):
			$pRes = $pRes->identity();
		endif;

		if ($pRole instanceof Zupal_Roles):
			$pRole = $pRole->identity();
		endif;

		$this->res->setValue($pRes);
		$this->role->setValue($pRole);

		$this->setAction(ZUPAL_BASEURL . DS . join(DS, array('admin', 'acl', 'grantValidate')));

		$this->setMethod('post');
	}

}