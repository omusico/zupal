<?

/**
 * Note -- this form like many Zupal forms has NO default action -- you have to set the action target externally
 */

class Zupal_Content_Form
extends Zupal_Nodes_Form
{
	public function __construct(Zupal_Content $pContent = NULL)
	{
		if (is_null($pContent)):
			$pContent = new Zupal_Content();
		endif;

		$ini_path = dirname(__FILE__) . DS . 'Form.ini';
		$config = new Zend_Config_Ini($ini_path, 'fields');
		$elements = $config->elements;
		parent::__construct($pContent, $config);
		$this->addDisplayGroup(array_keys($elements->toArray()), 'content', array('order' => -1, 'legend' => 'detail', 'style' =>"width: 400px"));
		$this->getDisplayGroup('content')->removeDecorator('DtDdWrapper');

		$root = Zend_Controller_Front::getInstance()->getBaseUrl() . DS . 'content' . DS . 'item';

		$this->setMethod('post');
	}

}