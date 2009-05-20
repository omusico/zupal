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
		foreach(Zupal_Media_Media::getInstance()->find_all() as $media):
			$this->media_id->addMultiOption($media->identity(), $media->name);
		endforeach;
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
			'person_born',
			'mb_id',
			'type'
		);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ parse_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pName
	* @return <type>
	*/
	public function parse_name ($pName)
	{
		$pName = trim(preg_replace('~ {2,}~', ' ', $pName));
		$name_parts = split(' ', $pName);

		$first = '';
		$middle = '';
		$last = '';

		switch(count($name_parts)):
			case 1:
				$first = array_pop($name_parts);
			break;

			case 2:
				list($first, $last) = $name_parts;
			break;

			case 3:
				list($first, $middle, $last) = $name_parts;
			break;

			case 0:
			break;

			default:
				$first = array_shift($name_parts);
				$last = array_pop($name_parts);
				$middle = join(' ', $name_parts);
		endswitch;

		$this->person_name_first->setValue($first);
		$this->person_name_middle->setValue($middle);
		$this->person_name_last->setValue($last);
	}
}