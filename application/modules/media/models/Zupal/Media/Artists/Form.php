<?

class Zupal_Media_Artists_Form
extends Zupal_Nodes_Form
{


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