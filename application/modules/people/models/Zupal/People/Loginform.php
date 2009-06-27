<?

class Zupal_People_Loginform
extends Zend_Form
{

	public function __construct()
	{
		$ini_path = dirname(__FILE__) . DS . 'Loginform.ini';
		$config = new Zend_Config_Ini($ini_path, 'fields');
		parent::__construct($config);
		$root =  ZUPAL_BASEURL . DS . 'people' . DS . 'user' . DS . 'loginvalidate';
		$this->setAction($root);
		$this->setMethod('post');
	}

}