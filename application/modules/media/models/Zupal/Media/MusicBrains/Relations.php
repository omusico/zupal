<?

class Zupal_Media_MusicBrains_Relations
extends Zupal_Domain_Abstract
{


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ table_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	/**
	 * @see CPF_Formset_Domain::get_table_class()
	 * -- note -- this is "boilerplate" code that can be put into any new domain
	 */
	public function tableClass ()
	{
		return preg_replace('~^Zupal_~', 'Zupal_Table_', get_class($this));
	}

	/**
	 * @see CPF_Formset_Domain::get()
	 *
	 * @param unknown_type $pID
	 * @return CPF_Formset_Domain
	 *
	 */
	public function get ($pID)
	{
		return new self($pID);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * note -- this "boilderplate" can be dropped anywhere.
 */
	private static $_Instance = NULL;

/**
 *
 * @return Zupal_Media_MusicBrains_Relations
 */
	public static function getInstance()
	{
		if (is_null(self::$_Instance)):
		// process
		self::$_Instance = new self();
		endif;
		return self::$_Instance;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ digest_list @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param SimpleXMLElement $pList
	* @return Zupal_Media_MusicBrains_Relations[]
	*/
	public static function digest_list (SimpleXMLElement $pList, $pFrom, $pFrom_type)
	{
		$list = array();
		$list_attrs = $pList->attributes();
		$pTarget_type = (string) $list_attrs['target-type'];
		foreach($pList->children() as $node_type => $relation_node):
			if (!strcasecmp($node_type, 'relation')):
				$list[] = self::digest_node($relation_node, $pFrom, $pFrom_type, $pTarget_type);
			endif;
		endforeach;
		return $list;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ digest_node @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param SimpleXMLElement $pNode
	* @return Zupal_Media_MusicBrains_Relations
	*/
	public static function digest_node (SimpleXMLElement $pNode, $pFrom, $pFrom_type, $pTarget_type = '')
	{

		$attrs = $pNode->attributes();

		$type = strtolower($attrs['type']);
		$target = $attrs['target'];
		
		$props = array(
			'from' => $pFrom,
			'from_type' => $pFrom_type,
			'target' => $target,
			'target_type' => $target_type,
			'type' => $type
		);
		
		$select = self::getInstance()->_select($props);
		$relation = self::getInstance()->findOne($select);
		
		if (!$relation):
			$relation = new self();
			foreach($props as $name => $value):
				$relation->name = $value;
			endforeach;

			switch ($pTarget_type):

				case 'artist':
					$relation->label = $pNode->artist->name;
				break;

				case 'track':
				case 'release':
					$relation->label = $pNode->release->title;
				break;

				case 'url':
					$relation->label = $target;
				break;

			endswitch;

			$relation->save();

			//@TODO: take advantage of inner content
		endif;
		return $relation;
	}
}