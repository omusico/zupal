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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ artistdataAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function artistsdataAction ()
	{
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