<?

class Zupal_Media_Artists_Find
extends Zend_Form
{

	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __construct @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function __construct ()
	{
		parent::__construct(new Zend_Config_Ini(dirname(__FILE__) . DS . 'Find.ini', 'fields'));
	}


}