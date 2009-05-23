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
		return self::digest_artist_or_group($client->get());
	}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_artist @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pID
	* @return <type>
	*/
	public static function get_release ($pID)
	{
		$client = new  Zend_Rest_Client("http://musicbrainz.org/ws/1/release/" . $pID);
		$client->inc('artist artist-rels');
		$client->type('xml');
		return self::digest_release($client->get());
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ digest_release @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pParam
	* @return <type>
	*/
	public static function digest_release ($pNode)
	{
		$release_attrs = $release->attributes();
		$type = (string) $release_attrs['type'];
		$artist = NULL;
		$relations = NULL;
		
		if ($pNode->artist):
			$artist = self::digest_artist_or_group($pNode->artist);
		endif;
		$id = (string) $release_attrs['id'];
		
		if ($pNode['relation-list']):
			$relations = self::digest_relat_list($id, $pNode['relation-list']);
		endif;
		
		$release = Zupal_Media_MBnodes_Release::factory($id);
		
		if ($artist):
			$release->set_artist($artist->get_id());
		endif;
		
		foreach($relations as $relation):
			$release->add_relation($relation);
		endforeach;
		
		return $release;
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
	public static function digest_relat_list ($id, $pParam, $ele_name)
	{
		$out = array();
		$attrs = $pParam->attributes();
		$target_type = (string) $attrs['target-type'];
		
		if ($id):

			foreach($pParam->relation as $key => $value):
				switch($key):
					case 'relation':
							$attrs = $value->attributes();
							$type = (string) $attrs->type;
							$begin = (string) $attrs->begin;
							$end = (string) $attrs->end;
							$target = (string) $attrs->target;
							
							$relationship = Zupal_Media_MBnodes_Relation::factory($id, $target);
							$relationship->set_type($target_type);
							$relationship->set_relationship($type);
							
							switch(strtolower($target_type)):
								case 'artist':
									$relationship->set_name((string) $value->artist->name);
								break;
								
								case 'release':
									$relationship->set_name((string) $value->release->title);
								break;
							endswitch;
							
							$relationship->set_begin($begin);
							$relationship->set_end($end);
							
							$out[] = $relationship;
					break;
				endswitch;
			endforeach;
			
		endif;
		
		return $out;
	}
	
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ digest_relat_item @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	public static function digest_relat_item($element, $k)
	{
		$type = '';
		$target = '';
		$name = '';
		
		$attrs = $element->attributes();
		switch($k):
			case 'artist':
				$type = (string) $attrs['type'];
				$target = (string) $attrs['id'];
				$name = (string) $element->name;
			break;
			
			case 'release':
				$type = (string) $attrs['type'];
				$target = (string) $attrs['id'];
				$name = (string) $element->title;
			break;
		endswitch;
		
		return array(
			'type' => $type,
			'target' => $target,
			'name' => $name
		);
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

	public static function digest_artist_or_group($node, $get_relations = TRUE)
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
					$relations = array_merge($relations, self::digest_relat_list($id, $element, $ele_name));
				break;
				
				default:
					// don't care
			endswitch;
			
		endforeach;
		$artist = Zupal_Media_MBnodes_Artist::factory($id);
		if ($name) $artist->set_name($name);
		if ($begin) $artist->set_born($begin);
		if ($end) $artist->set_died($end);
		if ($relations):
			foreach($relations as $relation):
				$artist->add_relation($relation);
			endforeach;		
		endif;
		return $artist;
	}

}