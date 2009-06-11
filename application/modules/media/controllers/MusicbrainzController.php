<?

class Media_MusicbrainzController
extends Zupal_Controller_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function indexAction ()
	{
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ loadAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function loadAction ()
	{
		$module = Zupal_Module_Manager::getInstance()->get('media');

		$dir_path = (string) $module->info()->musicolio_files;

		$di = new DirectoryIterator($dir_path);

		$this->view->files = array();

		foreach($di as $fi):
			if ($fi->isFile()):
				$this->view->files[$fi->getFilename()] = $fi->getRealPath();
			endif;
		endforeach;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ findartistAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function findartistAction ()
	{
	
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ artistsAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function artistsAction ()
	{
		$this->view;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ reloadAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function reloadAction ()
	{
		$beginswith = $this->_getParam('beginswith', NULL);
		$type = $this->_getParam('type', 'artist');
		$this->view->beginswith = $beginswith;

		error_log(__METHOD__ . ': type = ' . $type . ', bw = ' . $beginswith);
		$this->view->type = $type;
		$this->view->stop_reload = TRUE;
		switch($type):
		
			case 'artist':

				if (!is_null($beginswith)):
					$this->view->stop_reload = FALSE;
					$mba = Zupal_Musicbrainz_Artist::getInstance();
					$select = $mba->table()->select()->order('id')
					->limit(100, $beginswith * 100);

					$this->view->artists = $mba->find($select);
					if (!count($this->view->artists)):
						$this->view->stop_reload = TRUE;
					endif;
				else:
					$this->view->artists = array();
				endif;

				foreach($this->view->artists as $artist):
					$artist->json(TRUE);
				endforeach;
			break;

		endswitch;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ manageAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function manageAction ()
	{
		$this->view;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ dataAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function dataAction ()
	{
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		switch($this->_getParam('type')):
			case 'artist':
				
				$gid = $this->_getParam('gid');
				$key = str_replace('-', '_', $gid);
				$ac = Zupal_Media_MusicBrainz_Cache_Artists::getInstance();

				if (!$ac->test($key)):
					$artist = Zupal_Musicbrainz_Artist::getInstance()->findOne(array('gid' => $gid));
					$json = $artist->json(); // handles caching
				else:
					$json = $ac->load($key);
				endif;
				echo $json;
			break;

		endswitch;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ artistdataAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function artistsdataAction ()
	{
		try{
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		$artist = Zupal_Musicbrainz_Artist::getInstance();

		$data = array();
		$name = $this->_getParam('name', NULL);
		
		if ($name):
			$data['name'] = $name;
		endif;

		echo $artist->render_data($data,
				$this->_getParam('start', 0), 
				$this->_getParam('rows', 100),
				$this->_getParam('sort', 'name'));

		} catch(Exception $e){
			error_log(print_r($e, 1));
			echo '{}';
		}

	}
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ artistAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function artistAction ()
	{
		if ($this->_getParam('id')):
		$this->view->artist = new Zupal_Musicbrainz_Artist($this->_getParam('id'));
		endif;
	}
}