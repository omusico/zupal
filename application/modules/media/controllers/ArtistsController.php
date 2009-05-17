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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ findAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function findAction ()
	{
		$this->view->form = new Zupal_Media_Artists_Find();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ findvalidate @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	 * 
	* @return <type>
	*/
	public function findvalidateAction ()
	{
		$client = new  Zend_Rest_Client("http://musicbrainz.org/ws/1/artist/");
		$client->type('xml');
		$client->name(str_replace(' ', '+', $this->_getParam('find')));
		$result = $client->get();
		
		$this->view->result = $result;
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
		$artists = Zupal_Media_Artists::getInstance()->find_node($this->_getParam('node_id'));
		$this->view->artist = array_pop($artists);
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