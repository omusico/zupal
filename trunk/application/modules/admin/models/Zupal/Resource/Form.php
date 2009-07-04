<?

class Zupal_Resource_Form
extends Zupal_Form_Abstract
{

	public function __construct($pRole = NULL)
	{

		$ini_path = dirname(__FILE__) . DS . 'Form.ini';
		$config = new Zend_Config_Ini($ini_path, 'fields');

		parent::__construct($config);

		$root = Zend_Controller_Front::getInstance()->getBaseUrl() . DS . 'admin' . DS . 'acl' . DS;

		if (is_null($pRole)):
			$pRole = new Zupal_Resources();
		elseif (is_string($pRole)):
			$pRole = new Zupal_Resources($pRole);
		endif;

		$this->set_domain($pRole);

		if ($pRole->identity())
		{
			$this->setAction($root . 'resupdatevalidate');
		}
		else
		{
			$this->setAction($root  . 'resaddvalidate');
			$this->submit->setLabel('Create Resource');
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