<?php

class Zupal_Media_MusicBrains_Artists
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
		$artist = new self($pID);
		if (!$artist->isSaved()):
			$artist->load_from_mb();
		endif;
		return $artist;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * note -- this "boilderplate" can be dropped anywhere.
 */
	private static $_Instance = NULL;

/**
 *
 * @return Zupal_People
 */
	public static function getInstance()
	{
		if (is_null(self::$_Instance)):
		// process
			self::$_Instance = new self();
		endif;
		return self::$_Instance;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_from_mb @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param boolean $pInclude_relations)
	* @return boolean
	*/
	public function load_from_mb ($pInclude_relations)
	{
		if (!$this->identity()) return FALSE;

		$client = new  Zend_Rest_Client("http://musicbrainz.org/ws/1/artist/" . $pID);
		$client->inc('artist-rels release-rels');
		$client->type('xml');

		if ($client->artist):
		
			$artist_node = $client->artist;
			$attrs = $artist_node->attributes();
			$type = (string) $attrs['type'];
			$id_field = $this->table()->idField();
			$this->$id_field = (string) $attrs['id'];

			foreach($artist_node->children() as $ele_name => $element):

				switch($ele_name):
					case 'life-span':
						foreach ($element->attributes() as $life_prop => $life_value):
							$life_prop = strtolower($life_prop);
							switch($life_prop):
								case 'begin':
								case 'end':
									$this->$life_prop = $life_value;
							endswitch;
						endforeach;
					break;

					case 'name':
						$this->name = (string) $element;
					break;

					case 'sort-name': // don't care
					break;

					case 'relation-list':
						$list = Zupal_Media_MusicBrains_Relations::digest_list(
							$relations, $element, $this->identity(), 'artist'
						);
						$relations = array_merge($relations, $list);
					break;

					default:
						// don't care
				endswitch;

			endforeach;

			if ($relations):
				foreach($relations as $relation):
					$this->add_relation($relation);
				endforeach;
			endif;

			$this->save();
		else:
			return FALSE;
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ relations @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_relations = array();

	public function set_relation(Zupal_Media_MusicBrains_Relations $pRelat)
	{
		$this->_relations[$pRelat->identity()] = $pRelat;
	}

	public function get_relation($pID){ return $this->_relations[$pID]; }

	public function get_relations(){ return $this->_relations; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ search @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pSearch_string
	* @return <type>
	*/
	public static function search ($pSearch_string)
	{
		$client = new  Zend_Rest_Client("http://musicbrainz.org/ws/1/artist/");
		$client->type('xml');
		$client->name(str_replace(' ', '+', $pSearch_string));
		$result = $client->get();

		$out = array();

		foreach($result as $list):
			foreach($list->children() as $node_name => $node):
				if ($node_name == 'artist'):
				$artist = new stdClass();
				$attrs = $node->attributes();
				$artist->mbid = (string) $attrs['id'];
				$artist->type = (string) $attrs['type'];
				$artist->name = (string) $node->name;
				$artist->disambiguation = (string)$node->disambiguation;
				$out[$artist->mbid] = $artist;
				endif;
			endforeach;
		endforeach;
		return $out;
	}

}