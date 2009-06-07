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
		$client->inc('artist-rels');
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
		$release_node = $pNode->release;
		
		$release_attrs = $release_node->attributes();
		$type = (string) $release_attrs['type'];
		$artist = NULL;
		$relations = NULL;
		$id = (string) $release_attrs['id'];
		
		$release = Zupal_Media_MBnodes_Release::factory($id);
		$release->set_type($type);
		
		foreach($release_node->children() as $rel_name => $relation_item):
			switch($rel_name):
				case 'artist':
					$artist = self::digest_artist_or_group($relation_item);
					$release->set_artist($artist->get_id());
				break;
				
				case 'relation-list':
					$relations = self::digest_relat_list($id, $relation_item);
					foreach($relations as $relation):
						$release->add_relation($relation);
					endforeach;
				break;
				
				case 'title':
					$release->set_title((string) $relation_item);
				break;
			endswitch;
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
	public static function digest_relat_list ($id, $pParam)
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
		if ($node->artist) $node = $node->artist;
		$begin = '?';
		$end = '?';
		$name = '?';
		$attrs = $node->attributes();
		$type = (string) $attrs['type'];
		$id = (string) $attrs['id'];
		$relations = array();
		foreach($node->children()  as $ele_name => $element):

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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ is_string @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pString
	* @return <type>
	*/
	public static function id_string ($pString)
	{
		$out = '';
		$set = preg_replace('~[\W]+~', '', $pString);
		$data = array_chunk(str_split($set), 3);

		foreach($data as $triplet):
			$char = $triplet[0];

			while(count($triplet) < 3) $triplet[] = 0;
			$color = strtolower(join('', $triplet));

			foreach($triplet as $i => $h):
				$h = strtolower($h);
				if (!array_key_exists($h, self::$_hue)):
				//	error_log(__METHOD__ . ': no key for (' . $h . ')');
					continue 2;
				endif;
				 $triplet[$i] = self::$_hue[$h];
			endforeach;

			$out .= sprintf('<span class="c%s">%s</span>', $color, $char, $color);

			list($r, $g, $b) = $triplet;
			if (!array_key_exists($color, self::$_colors)):
				self::$_colors[$color] = self::rgb2html($r, $g, $b);
			endif;
		endforeach;

		return $out;
	}
	
	private static $_hue = array(
		0 => 255,
		1 => 25,
		2 => 50,
		3 => 75, 
		4 => 100, 
		5 => 125,
		6 => 150,
		7 => 175,
		8 => 200,
		9 => 225,
'a' => 0,
'b' => 51,
'c' => 102,
'd' => 153,
'e' => 204,
'f' => 255,
'g' => 0,
'h' => 51,
'i' => 102,
'j' => 153,
'k' => 204,
'l' => 255,
'm' => 0,
'n' => 51,
'o' => 102,
'p' => 153,
'q' => 204,
'r' => 255,
's' => 0,
't' => 51,
'u' => 102,
'v' => 153,
'w' => 204,
'x' => 255,
'y' => 0,
'z' => 51,
	);
	
	private static $_colors = array();
	
	private static function rgb2html($r, $g=-1, $b=-1)
	{
		if (is_array($r) && sizeof($r) == 3)
			list($r, $g, $b) = $r;

		$r = intval($r); $g = intval($g);
		$b = intval($b);

		$r = dechex($r<0?0:($r>255?255:$r));
		$g = dechex($g<0?0:($g>255?255:$g));
		$b = dechex($b<0?0:($b>255?255:$b));

		$color = (strlen($r) < 2?'0':'').$r;
		$color .= (strlen($g) < 2?'0':'').$g;
		$color .= (strlen($b) < 2?'0':'').$b;
		return '#'.$color;
	}

	public static function color_css()
	{
		$odd = TRUE;
		ob_start();
		?>
	<? foreach (self::$_colors as $key => $value): $odd = !$odd; ?>
<? if ($odd): ?>
	.c<?= $key ?> {color: <?= $value ?>; font-weight: bold;}
<? else: ?>
	.c<?= $key ?> {color: <?= $value ?>;}
<? endif; ?>
	<? endforeach; ?>
		<?
		return ob_get_clean();
	}
}