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
		$id = $this->_getParam('mb_id');
		$mb_artist = Zupal_Media_MusicBrains_Artists::getInstance()->get($id);
		
		$form = new Zupal_Media_Artists_Form();
		$form->performs_as->setValue($mb_artist->name);
		$form->mb_id->setValue($mb_artist->mb_id);
		$form->type->setValue(strtolower($mb_artist->type));
		
		$form->media_id->setValue(1);
		
		if (!strcasecmp('person',$mb_artist->type)):
			$form->parse_name($mb_artist->name);
			$form->person_born->setValue($mb_artist->begin);
		endif;

		$this->view->form = $form;
		$this->view->mb_artist = $mb_artist;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ findvalidate @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	 * @return <type>
	 */
	public function findvalidateAction ()
	{		
		$this->view->artists = Zupal_Media_MusicBrains_Artists::search($this->_getParam('find'));
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ delete @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function deleteAction ()
	{
		$artist = Zupal_Media_Artists::getInstance()->find_node($this->_getParam('node_id'));
		$artist->delete();
		$this->_forward('index', NULL, NULL, array('message' => $artist . ' deleted'));
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ viewAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function viewAction ()
	{
		$mb_id = $this->_getParam('mb_id');

		if ($mb_id):
			$artist = Zupal_Media_Artists::getInstance()->find_mb($mb_id);
		else:
			$artist = Zupal_Media_Artists::getInstance()->find_node($this->_getParam('node_id'));
		endif;
		$this->view->artist = $artist;
		if ($artist->mb_id):
			$this->view->artist_mb = $artist->mb_artist();
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
			$this->_forward('view', NULL, NULL,
				array('message' => $form->get_domain() . ' saved.' ,
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