<?

class Zupal_Media_Musicbrainz_Seo
extends Zend_Controller_Plugin_Abstract
{
	public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
		$uri = $request->getRequestUri();

		$uri = preg_replace('~^' . ZUPAL_BASEURL . '~', '', $uri);

		if (preg_match('~^/mb/artist/([^/?]+)~', $uri, $match)):
			$key = $match[1];
			if(is_numeric($key)):
				$artist = new Zupal_Musicbrainz_Artist($key);
				$key = $artist->gid;
			endif;
			error_log(__METHOD__ . ': ' . $key);
			$request->setRequestUri(ZUPAL_BASEURL . '/media/musicbrainz/data/type/artist/gid/'. $key);
		endif;
    }

}