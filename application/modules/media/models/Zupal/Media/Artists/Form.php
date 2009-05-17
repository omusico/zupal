<?

class Zupal_Media_Artists_Form
extends Zupal_Nodes_Form
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __construct @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pArtist = NULL
	* @return <type>
	*/
	public function __construct ($pArtist = NULL)
	{
		if (is_null($pArtist)) $pArtist = new Zupal_Media_Artists();
		parent::__construct($pArtist, new Zend_Config_Ini(dirname(__FILE__) . DS . 'Form.ini', 'fields'));
	}
	

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function domain_fields ()
	{
		return array('performs_as',
			'person_name_first',
			'person_name_middle',
			'person_name_last',
			'media_id',
			'person_born'
		);
	}


}