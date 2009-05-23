<?

class Media_ArtistsController
extends Zupal_Controller_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function indexAction ()
	{
		$this->view->artist_stub = Zupal_Media_Artists::getInstance();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ editAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function editAction ()
	{
		$node = Zupal_Media_Artists::getInstance()->find_node($this->_getParam('node_id'));
		$form = new Zupal_Media_Artists_Form($node);
		$form->setAction(ZUPAL_BASEURL . '/media/artists/editvalidate');

		$this->view->form = $form;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ findAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function findAction ()
	{
		$this->view->form = new Zupal_Media_Artists_Find();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ mbartistAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	 *
	 */

	public function mbartistAction ()
	{
		$id = $this->_getParam('musicbrainz_id');
		$data = Zupal_Media_MusicBrains::get_artist($id);
		
		$this->view->form = new Zupal_Media_Artists_Form();
		$this->view->form->performs_as->setValue($data['name']);
		$this->view->form->mb_id->setValue($id);
		$this->view->form->type->setValue(strtolower($data['type']));
		$this->view->form->media_id->setValue(1);
		$this->view->form->person_born->setValue($data['born']);
		
		if (!strcasecmp('person', $data['type'])):
			$this->view->form->parse_name($data['name']);
		endif;
		$this->view->data = $data;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ findvalidate @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	 * @return <type>
	 */
	public function findvalidateAction ()
	{		
		$this->view->result = Zupal_Media_MusicBrains::find_artist($this->_getParam('find'));
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ newAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function newAction ()
	{
		$this->view->form = new Zupal_Media_Artists_Form(new Zupal_Media_Artists());
		$this->view->form->setAction(ZUPAL_BASEURL . DS . 'media/artists/newvalidate');
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ newvalidateAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function newvalidateAction ()
	{
		$form = new Zupal_Media_Artists_Form();
		if ($form->isValid($this->_getAllParams())):
			$form->save();
			$this->_forward('view', NULL, NULL, array('node_id' => $form->get_domain()->nodeId()));
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ viewAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function viewAction ()
	{
		$artist = Zupal_Media_Artists::getInstance()->find_node($this->_getParam('node_id'));
		$this->view->artist = $artist;
		if ($this->view->artist->mb_id):
			$this->view->data = array_pop(
				Zupal_Media_MusicBrains::get_artist_relat($this->view->artist->mb_id)
			);
		else:
			$this->view->data = NULL;
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ editvalidate @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function editvalidateAction ()
	{
		$as = Zupal_Media_Artists::getInstance()->find_node($this->_getParam('node_id'));

		$form = new Zupal_Media_Artists_Form($as);
		
		if($form->isValid($this->_getAllParams())):
			$form->save();
			$this->_forward('view', NULL, NULL, array('message' => $form->get_domain() . ' saved.' ,
				'node_id' => $form->node_id->getValue()));
		endif;
		
	}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ dataAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function dataAction ()
	{
        $this->_helper->layout->disableLayout();
		$this->view->data = Zupal_Media_Artists::getInstance()->render_data(array(), 'node_id');
	}
}