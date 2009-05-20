<?

class Zupal_Media_MusicBrains
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_artist @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pID
	* @return <type>
	*/
	public function get_artist ($pID)
	{
		$client = new  Zend_Rest_Client("http://musicbrainz.org/ws/1/artist/" . $pID);
		$client->type('xml');
		return array_pop(self::digest_artist_list($client->get()));
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_artist_relat @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pID
	* @return <type>
	*/
	public static function get_artist_relat ($pID)
	{
		$client = new  Zend_Rest_Client("http://musicbrainz.org/ws/1/artist/" . $pID);
		$client->inc('artist-rels release-rels');
		$client->type('xml');
		return self::digest_artist_list($client->get());
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ find_artist @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pString
	* @return <type>
	*/
	public static function find_artist ($pString)
	{
		$client = new  Zend_Rest_Client("http://musicbrainz.org/ws/1/artist/");
		$client->type('xml');
		$client->name(str_replace(' ', '+', $pString));
		return $client->get();
	}

	public static function digest_result($pNode)
	{
		$out = array();
		foreach($pNode as $k => $root_node):
			switch ($k):
				case 'artist-list':
					$out['artist-list'] = self::digest_artist_list($root_node);
				break;

				case 'relation-list':
					$out['relation-list'] = self::digest_relat($root_node);
				break;
			endswitch;
		endforeach;
		return $out;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ digest_relat @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pParam
	* @return <type>
	*/
	public static function digest_relat_list ($pParam)
	{
		$out = array('releases' => array(),
			'relation' => array(),
			'artist' => array()
		);

		foreach($pParam as $k => $element):
			switch($k):
				case 'relation':
					foreach($element as $r => $relat):
						$out[$r][] = self::digest_relat_item($relat, $r);
					endforeach;
				break;
			endswitch;
		endforeach;
		
		return $out;
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ digest_relat_item @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public static function digest_relat_item($element, $k)
	{
		
		switch($k):
			case 'artist':
				$attrs = $element->attributes();
				$type = (string) $attrs['type'];
				$target = (string) $attrs['id'];
				$name = (string) $element->name;
				return array(
					'type' => $type,
					'target' => $target,
					'name' => $name
				);
			break;
			
			default: 
				return $k . ': ' . print_r($element, 1);
		endswitch;
		
	}

	public static function digest_artist_list($root_node)
	{
		$out = array();
		foreach($root_node as $type => $node):
			switch($type):
				case 'artist':
				$out[] = self::digest_artist_or_group($node);
				break; // end case artist
			endswitch;
		endforeach;
		return $out;
	}

	public static function digest_artist_or_group($node)
	{
		$begin = '?';
		$end = '?';
		$name = '?';
		$attrs = $node->attributes();
		$type = (string) $attrs['type'];
		$id = (string) $attrs['id'];
		$relations = array();
		
		foreach($node  as $ele_name => $element):

			switch($ele_name):
				case 'life-span':
					foreach ($element->attributes() as $life_prop => $life_value):
					//	echo '<h5>', $life_prop, ' = ' , $life_value, '</h5>';
						$$life_prop = (string) $life_value;
					endforeach;
				break;

				case 'name':
					$name = (string) $element;
				break;

				case 'sort-name': // don't care
				break;

				case 'relation-list':
					$relations[] = self::digest_relat_list($element);
				break;
				
				default:
					// don't care
			endswitch;
		endforeach;

		return array(
			'id' => $id,
			'name' => $name,
			'born' => $begin,
			'died' => $end,
			'type' => $type,
			'relations' => $relations
		);
	}
}